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
        $subscription = $organization?->subscription;

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
        if ($organization->subscription()->where('status', 'active')->exists()) {
            return redirect()->route('organization.billing.index')
                ->with('info', 'You already have an active subscription.');
        }

        // Calculate 12-month donation total to recommend a tier
        $total12m = $tierService->calculate12MonthDonations($organization);

        // Determine recommended tier (default to Tier 1 if no donations yet)
        $recommendedTier = $tierService->determineTierByAmount($total12m);
        if (!$recommendedTier) {
            $recommendedTier = SubscriptionTier::active()->ordered()->first();
        }

        return view('organization.billing.create', compact('recommendedTier'));
    }

    /**
     * Store new subscription (process Stripe payment)
     */
    public function store(Request $request, StripeService $stripeService)
    {
        $request->validate([
            'tier_id' => 'required|exists:subscription_tiers,id',
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
        $tier = SubscriptionTier::findOrFail($request->tier_id);

        DB::beginTransaction();

        try {
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

            // 2. Attach payment method to customer
            $stripeService->attachPaymentMethod($request->payment_method, $customer->id);

            // 3. Set as default payment method
            \Stripe\Customer::update($customer->id, [
                'invoice_settings' => [
                    'default_payment_method' => $request->payment_method,
                ],
            ]);

            // 4. Create Stripe subscription
            $stripeSubscription = $stripeService->createSubscription(
                $customer->id,
                $tier->stripe_price_id
            );

            // 5. Get payment method details for display
            $paymentMethod = \Stripe\PaymentMethod::retrieve($request->payment_method);

            // 6. Create local subscription record
            $subscription = Subscription::create([
                'organization_id' => $organization->id,
                'tier_id' => $tier->id,
                'plan' => $tier->name, // For backward compatibility
                'price' => $tier->monthly_fee,
                'status' => $stripeSubscription->status,
                'stripe_customer_id' => $customer->id,
                'stripe_subscription_id' => $stripeSubscription->id,
                'stripe_price_id' => $tier->stripe_price_id,
                'stripe_status' => $stripeSubscription->status,
                'current_period_start' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_start),
                'current_period_end' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end),
                'next_billing_date' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end),
                'payment_method_last4' => $paymentMethod->card->last4 ?? null,
                'payment_method_brand' => $paymentMethod->card->brand ?? null,
            ]);

            DB::commit();

            Log::info('Subscription created successfully', [
                'organization_id' => $organization->id,
                'subscription_id' => $subscription->id,
                'tier' => $tier->name,
            ]);

            return redirect()->route('organization.dashboard')
                ->with('success', 'Subscription activated successfully! Welcome to ' . $tier->name . '.');

        } catch (\Stripe\Exception\CardException $e) {
            DB::rollBack();
            Log::error('Stripe card error during subscription creation', [
                'error' => $e->getMessage(),
                'organization_id' => $organization->id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Card Error: ' . $e->getMessage());

        } catch (\Stripe\Exception\ApiErrorException $e) {
            DB::rollBack();
            Log::error('Stripe API error during subscription creation', [
                'error' => $e->getMessage(),
                'organization_id' => $organization->id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Payment processing failed. Please try again.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating subscription', [
                'error' => $e->getMessage(),
                'organization_id' => $organization->id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred. Please try again or contact support.');
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
