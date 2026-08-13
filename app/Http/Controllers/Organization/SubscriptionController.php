<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionTier;
use App\Services\StripeService;
use App\Services\SubscriptionTierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Plan definitions
     */
    public static array $plans = [
        'basic' => [
            'name'        => 'Basic',
            'price'       => 5.00,
            'currency'    => 'EUR',
            'billing'     => 'monthly',
            'campaigns'   => 3,
            'devices'     => 2,
            'features'    => [
                '3 active campaigns',
                '2 paired devices',
                'Basic analytics',
                'CSV export',
                'Email support',
            ],
        ],
        'premium' => [
            'name'        => 'Premium',
            'price'       => 10.00,
            'currency'    => 'EUR',
            'billing'     => 'monthly',
            'campaigns'   => null, // unlimited
            'devices'     => 10,
            'features'    => [
                'Unlimited campaigns',
                '10 paired devices',
                'Advanced analytics & charts',
                'CSV export',
                'Priority support',
                'Custom branding',
            ],
        ],
    ];

    /**
     * Show the subscription / billing overview page
     */
    public function index()
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('error', 'Please complete your organization profile first.');
        }

        $subscription = $organization->subscription;

        // Calculate 12-month donation total
        $total12m = $organization->donations()
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', now()->subYear())
            ->sum('amount');

        // Get current tier
        $currentTier = $subscription?->tier;

        // Get next tier
        $nextTier = \App\Models\SubscriptionTier::where('min_amount', '>', $total12m)
            ->orderBy('min_amount', 'asc')
            ->first();

        // Calculate progress to next tier
        $progress = 0;
        if ($nextTier) {
            $progress = min(100, ($total12m / $nextTier->min_amount) * 100);
        }

        // Usage stats
        $usedCampaigns = $organization->campaigns()->count();
        $usedDevices = $organization->devices()->count();

        // Check for pending tier change
        $pendingTierChange = $organization->tierChangeLogs()
            ->where('status', 'pending')
            ->where('scheduled_date', '>', now())
            ->latest()
            ->first();

        return view('organization.billing.index', compact(
            'organization',
            'subscription',
            'currentTier',
            'nextTier',
            'total12m',
            'progress',
            'usedCampaigns',
            'usedDevices',
            'pendingTierChange',
        ));
    }

    /**
     * Show plan selection / upgrade page
     */
    public function plans()
    {
        $organization = auth()->user()->organization;
        $subscription = $organization?->subscription;
        $currentTierId = $subscription?->tier_id;

        // Get all active tiers
        $tiers = \App\Models\SubscriptionTier::active()->ordered()->get();

        // Calculate 12-month total
        $total12m = $organization->donations()
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', now()->subYear())
            ->sum('amount');

        return view('organization.billing.plans', compact(
            'subscription',
            'currentTierId',
            'tiers',
            'total12m'
        ));
    }

    /**
     * Show subscription creation page (initial subscription setup)
     */
    public function create(SubscriptionTierService $tierService)
    {
        $organization = auth()->user()->organization;

        // Check if already subscribed
        // Note: 'trialing' status from Stripe is mapped to 'active' in our database
        if ($organization->subscription()->where('status', 'active')->exists()) {
            return redirect()->route('organization.billing.index')
                ->with('info', 'You already have an active subscription.');
        }

        // All organizations start with Tier 1; the system auto-adjusts based on
        // the rolling 12-month donation total. We pass every tier so the user
        // can see exactly how pricing scales with their success.
        $tiers = SubscriptionTier::active()->ordered()->get();
        $tier1 = $tiers->first();

        return view('organization.billing.create', compact('tier1', 'tiers'));
    }

    /**
     * Store new subscription (process Stripe payment)
     */
    public function store(Request $request, StripeService $stripeService)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'billing_country' => 'required|string|max:2',
            'vat_number' => 'nullable|string|max:50',
            'terms_accepted' => 'required|accepted',
        ]);

        $organization = auth()->user()->organization;

        if (!$organization) {
            Log::error('Subscription creation failed: No organization found', [
                'user_id' => auth()->id(),
            ]);
            return redirect()->back()
                ->with('error', 'Organization not found. Please complete your profile first.');
        }

        // Check if already has active subscription
        // Note: 'trialing' status from Stripe is mapped to 'active' in our database
        if ($organization->subscription()->where('status', 'active')->exists()) {
            return redirect()->route('organization.billing.index')
                ->with('info', 'You already have an active subscription.');
        }

        // Always subscribe to Tier 1 - system will auto-upgrade based on donations
        $tier = SubscriptionTier::active()->ordered()->first();

        if (!$tier) {
            Log::error('Subscription creation failed: No active tiers found');
            return redirect()->back()
                ->with('error', 'No subscription tiers available. Please contact support.');
        }

        // Validate tier has Stripe Price ID
        if (empty($tier->stripe_price_id)) {
            Log::error('Subscription creation failed: Tier missing Stripe Price ID', [
                'tier_id' => $tier->id,
                'tier_name' => $tier->name,
            ]);
            return redirect()->back()
                ->with('error', 'Subscription configuration error. Please contact support.');
        }

        DB::beginTransaction();

        try {
            Log::info('Starting subscription creation', [
                'organization_id' => $organization->id,
                'organization_name' => $organization->name,
                'tier_id' => $tier->id,
                'tier_name' => $tier->name,
                'stripe_price_id' => $tier->stripe_price_id,
            ]);
            // 1. Create or get Stripe customer
            if (!$organization->stripe_customer_id) {
                $customer = $stripeService->createCustomer(
                    $organization->email ?? $request->billing_email,
                    $request->billing_name,
                    [
                        'organization_id' => $organization->id,
                        'organization_name' => $organization->name,
                    ]
                );
                $organization->update(['stripe_customer_id' => $customer->id]);
            } else {
                $customer = \Stripe\Customer::retrieve($organization->stripe_customer_id);
            }

            // 2. Attach payment method to customer and set as default
            $stripeService->attachPaymentMethod($request->payment_method, $customer->id);

            // 3. Create Stripe subscription with 30-day trial period
            // Note: First payment will be charged after 30 days
            $stripeSubscription = $stripeService->createSubscription(
                $customer->id,
                $tier->stripe_price_id,
                [
                    'organization_id' => $organization->id,
                    'organization_name' => $organization->name,
                ],
                30 // 30-day trial period before first payment
            );

            // 4. Get payment method details for display
            $paymentMethod = \Stripe\PaymentMethod::retrieve($request->payment_method);

            // Stripe API v2024-08-15+ moved current_period_start/end onto subscription items.
            // Resolve the period defensively across API versions, with trial fields and
            // a safe local fallback (we know we requested a 30-day trial).
            $firstItem = $stripeSubscription->items->data[0] ?? null;

            $periodStartTs = $stripeSubscription->current_period_start
                ?? ($firstItem->current_period_start ?? null)
                ?? $stripeSubscription->trial_start
                ?? $stripeSubscription->start_date
                ?? null;

            $periodEndTs = $stripeSubscription->current_period_end
                ?? ($firstItem->current_period_end ?? null)
                ?? $stripeSubscription->trial_end
                ?? null;

            $periodStart = $periodStartTs ? \Carbon\Carbon::createFromTimestamp($periodStartTs) : now();
            $periodEnd = $periodEndTs ? \Carbon\Carbon::createFromTimestamp($periodEndTs) : now()->addDays(30);

            // 5. Create local subscription record
            // Map tier to plan enum value for backward compatibility
            $planValue = $tier->id <= 3 ? 'basic' : 'premium';

            // Map Stripe status to database enum values
            // Database only allows: 'active', 'cancelled', 'suspended', 'expired'
            $statusMap = [
                'trialing' => 'active',
                'active' => 'active',
                'past_due' => 'active',
                'canceled' => 'cancelled',
                'unpaid' => 'suspended',
                'incomplete' => 'suspended',
                'incomplete_expired' => 'expired',
            ];
            $localStatus = $statusMap[$stripeSubscription->status] ?? 'active';

            $subscription = Subscription::create([
                'organization_id' => $organization->id,
                'tier_id' => $tier->id,
                'plan' => $planValue, // For backward compatibility: 'basic' or 'premium'
                'price' => $tier->monthly_fee,
                'status' => $localStatus, // Map Stripe status to local enum
                'stripe_customer_id' => $customer->id,
                'stripe_subscription_id' => $stripeSubscription->id,
                'stripe_price_id' => $tier->stripe_price_id,
                'stripe_status' => $stripeSubscription->status, // Keep original Stripe status
                'current_period_start' => $periodStart,
                'current_period_end' => $periodEnd,
                'next_billing_date' => $periodEnd,
                'payment_method_last4' => $paymentMethod->card->last4 ?? null,
                'payment_method_brand' => $paymentMethod->card->brand ?? null,
            ]);

            DB::commit();

            Log::info('Subscription created successfully', [
                'organization_id' => $organization->id,
                'subscription_id' => $subscription->id,
                'tier' => $tier->name,
                'trial_period_days' => 30,
            ]);

            return redirect()->route('organization.dashboard')
                ->with('success', 'Subscription activated successfully! Your first payment will be charged in 30 days. Welcome to ' . $tier->name . '!');

        } catch (\Stripe\Exception\CardException $e) {
            DB::rollBack();
            Log::error('Stripe card error during subscription creation', [
                'error' => $e->getMessage(),
                'error_code' => $e->getStripeCode(),
                'organization_id' => $organization->id,
                'tier_id' => $tier->id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Card Error: ' . $e->getMessage());

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            DB::rollBack();
            Log::error('Stripe invalid request error during subscription creation', [
                'error' => $e->getMessage(),
                'organization_id' => $organization->id,
                'tier_id' => $tier->id,
                'stripe_price_id' => $tier->stripe_price_id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Subscription configuration error. The subscription plan may not be properly configured. Please contact support.');

        } catch (\Stripe\Exception\ApiErrorException $e) {
            DB::rollBack();
            Log::error('Stripe API error during subscription creation', [
                'error' => $e->getMessage(),
                'error_type' => get_class($e),
                'organization_id' => $organization->id,
                'tier_id' => $tier->id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Payment processing failed: ' . $e->getMessage() . ' Please verify your Stripe configuration or try again later.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Unexpected error creating subscription', [
                'error' => $e->getMessage(),
                'error_type' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'organization_id' => $organization->id,
                'tier_id' => $tier->id ?? null,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage() . ' Please try again or contact support.');
        }
    }

    /**
     * Initiate a plan change (stub – real Stripe integration comes later)
     */
    public function changePlan(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:basic,premium',
        ]);

        $organization = auth()->user()->organization;
        $newPlan      = $request->plan;
        $planData     = self::$plans[$newPlan];

        // For now: just update the subscription record directly (Stripe integration later)
        if ($organization->subscription) {
            $organization->subscription->update([
                'plan'              => $newPlan,
                'price'             => $planData['price'],
                'status'            => 'active',
                'current_period_start' => now(),
                'current_period_end'   => now()->addMonth(),
                'next_billing_date'    => now()->addMonth(),
            ]);
        } else {
            Subscription::create([
                'organization_id'      => $organization->id,
                'plan'                 => $newPlan,
                'price'                => $planData['price'],
                'status'               => 'active',
                'current_period_start' => now(),
                'current_period_end'   => now()->addMonth(),
                'next_billing_date'    => now()->addMonth(),
            ]);
        }

        return redirect()->route('organization.billing.index')
            ->with('completed', "Plan changed to {$planData['name']} successfully!");
    }
}
