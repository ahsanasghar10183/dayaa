<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

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
            ->with('success', "Plan changed to {$planData['name']} successfully!");
    }
}
