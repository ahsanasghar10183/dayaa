<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\SubscriptionTier;
use App\Models\TierChangeLog;
use App\Models\Donation;
use Carbon\Carbon;

class SubscriptionTierService
{
    /**
     * Calculate 12-month donation total for an organization
     */
    public function calculate12MonthTotal(Organization $organization): float
    {
        $twelveMonthsAgo = Carbon::now()->subYear();

        return (float) $organization->donations()
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', $twelveMonthsAgo)
            ->sum('amount');
    }

    /**
     * Get the appropriate tier for a given fundraising amount
     */
    public function getTierForAmount(float $amount): ?SubscriptionTier
    {
        return SubscriptionTier::active()
            ->ordered()
            ->get()
            ->first(function ($tier) use ($amount) {
                return $tier->isInRange($amount);
            });
    }

    /**
     * Check if tier should change after a donation
     * Returns TierChangeLog if change is needed, null otherwise
     */
    public function checkTierAfterDonation(Donation $donation): ?TierChangeLog
    {
        $organization = $donation->organization;
        $subscription = $organization->subscription;

        if (!$subscription) {
            // Create default subscription if doesn't exist
            $freeTier = SubscriptionTier::where('name', 'Free')->first();
            $subscription = Subscription::create([
                'organization_id' => $organization->id,
                'tier_id' => $freeTier->id,
                'price' => 0,
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
                'next_billing_date' => now()->addMonth(),
            ]);
        }

        // Calculate 12-month total
        $total12m = $this->calculate12MonthTotal($organization);

        // Get appropriate tier for current total
        $newTier = $this->getTierForAmount($total12m);

        if (!$newTier) {
            return null;
        }

        // Check if tier changed
        if ($newTier->id === $subscription->tier_id) {
            return null; // No change needed
        }

        // Schedule tier change for next billing date
        return $this->scheduleTierChange(
            $organization,
            $subscription->tier_id,
            $newTier->id,
            $total12m,
            'donation'
        );
    }

    /**
     * Schedule a tier change
     */
    public function scheduleTierChange(
        Organization $organization,
        ?int $fromTierId,
        int $toTierId,
        float $donationTotal,
        string $triggeredBy = 'donation'
    ): TierChangeLog {
        $subscription = $organization->subscription;

        // Cancel any existing pending tier changes
        TierChangeLog::where('organization_id', $organization->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled', 'notes' => 'Superseded by new tier change']);

        // Create new tier change log
        return TierChangeLog::create([
            'organization_id' => $organization->id,
            'from_tier_id' => $fromTierId,
            'to_tier_id' => $toTierId,
            'triggered_by' => $triggeredBy,
            'status' => 'pending',
            'donation_total_12m' => $donationTotal,
            'scheduled_date' => $subscription->next_billing_date ?? now()->addMonth(),
        ]);
    }

    /**
     * Apply a pending tier change
     */
    public function applyTierChange(TierChangeLog $tierChangeLog): bool
    {
        if (!$tierChangeLog->isPending()) {
            return false;
        }

        $organization = $tierChangeLog->organization;
        $subscription = $organization->subscription;
        $newTier = $tierChangeLog->toTier;

        if (!$subscription || !$newTier) {
            return false;
        }

        // Update subscription
        $subscription->update([
            'tier_id' => $newTier->id,
            'price' => $newTier->monthly_fee,
            'plan' => null, // Nullify old plan field
        ]);

        // Mark tier change as applied
        $tierChangeLog->markAsApplied();

        // TODO: Update Stripe subscription when Stripe is integrated

        return true;
    }

    /**
     * Process all pending tier changes that are due
     */
    public function processPendingTierChanges(): int
    {
        $pendingChanges = TierChangeLog::pending()
            ->scheduledFor(now())
            ->with(['organization', 'toTier'])
            ->get();

        $processed = 0;

        foreach ($pendingChanges as $change) {
            if ($this->applyTierChange($change)) {
                $processed++;
            }
        }

        return $processed;
    }

    /**
     * Recalculate tier for an organization (used by scheduled jobs)
     */
    public function recalculateOrganizationTier(Organization $organization): ?TierChangeLog
    {
        $subscription = $organization->subscription;

        if (!$subscription) {
            return null;
        }

        $total12m = $this->calculate12MonthTotal($organization);
        $appropriateTier = $this->getTierForAmount($total12m);

        if (!$appropriateTier || $appropriateTier->id === $subscription->tier_id) {
            return null; // No change needed
        }

        return $this->scheduleTierChange(
            $organization,
            $subscription->tier_id,
            $appropriateTier->id,
            $total12m,
            'scheduled'
        );
    }

    /**
     * Recalculate tiers for all organizations (daily job)
     */
    public function recalculateAllOrganizationTiers(): array
    {
        $organizations = Organization::where('status', 'active')
            ->with('subscription')
            ->get();

        $results = [
            'total' => $organizations->count(),
            'changes_scheduled' => 0,
            'no_change' => 0,
        ];

        foreach ($organizations as $organization) {
            $tierChange = $this->recalculateOrganizationTier($organization);

            if ($tierChange) {
                $results['changes_scheduled']++;
            } else {
                $results['no_change']++;
            }
        }

        return $results;
    }
}
