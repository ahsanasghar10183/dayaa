<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\SubscriptionTier;
use App\Models\TierChangeLog;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SubscriptionTierService
{
    /**
     * Check if organization's tier should change based on 12-month donations
     * Called after every completed donation
     */
    public function checkAndScheduleTierChange(Organization $organization): ?TierChangeLog
    {
        try {
            // Calculate total donations in last 12 months
            $totalLast12Months = $this->calculate12MonthDonations($organization);

            // Determine appropriate tier based on total
            $appropriateTier = $this->determineTierByAmount($totalLast12Months);

            // Get current tier
            $currentTierId = $organization->subscription?->tier_id;

            // If tier should change
            if ($appropriateTier && $appropriateTier->id !== $currentTierId) {
                return $this->scheduleTierChange(
                    $organization,
                    $currentTierId,
                    $appropriateTier->id,
                    $totalLast12Months
                );
            }

            Log::info('No tier change needed for organization', [
                'organization_id' => $organization->id,
                'current_tier_id' => $currentTierId,
                'total_12m' => $totalLast12Months,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error checking tier change', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Calculate total donations in last 12 months
     */
    public function calculate12MonthDonations(Organization $organization): float
    {
        $twelveMonthsAgo = Carbon::now()->subMonths(12);

        $total = Donation::where('organization_id', $organization->id)
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', $twelveMonthsAgo)
            ->sum('amount');

        return round($total, 2);
    }

    /**
     * Determine appropriate tier based on donation amount
     */
    public function determineTierByAmount(float $amount): ?SubscriptionTier
    {
        return SubscriptionTier::where('is_active', true)
            ->where('min_amount', '<=', $amount)
            ->where(function ($query) use ($amount) {
                $query->whereNull('max_amount')
                    ->orWhere('max_amount', '>', $amount);
            })
            ->orderBy('min_amount', 'asc')
            ->first();
    }

    /**
     * Schedule a tier change for next billing date
     */
    public function scheduleTierChange(
        Organization $organization,
        ?int $fromTierId,
        int $toTierId,
        float $donationTotal
    ): TierChangeLog {
        DB::beginTransaction();

        try {
            // Get next billing date
            $nextBillingDate = $this->getNextBillingDate($organization);

            // Create tier change log
            $tierChangeLog = TierChangeLog::create([
                'organization_id' => $organization->id,
                'from_tier_id' => $fromTierId,
                'to_tier_id' => $toTierId,
                'triggered_by' => 'donation',
                'triggered_at' => now(),
                'scheduled_for' => $nextBillingDate,
                'status' => 'pending',
                'donation_total_12m' => $donationTotal,
                'notification_sent' => false,
            ]);

            // Update subscription with pending tier
            if ($organization->subscription) {
                $organization->subscription->update([
                    'pending_tier_id' => $toTierId,
                ]);
            }

            DB::commit();

            // Send notification email (non-blocking)
            $this->sendTierChangeNotification($organization, $tierChangeLog);

            Log::info('Tier change scheduled', [
                'organization_id' => $organization->id,
                'from_tier' => $fromTierId,
                'to_tier' => $toTierId,
                'scheduled_for' => $nextBillingDate,
            ]);

            return $tierChangeLog;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error scheduling tier change', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get organization's next billing date
     */
    public function getNextBillingDate(Organization $organization): Carbon
    {
        if ($organization->subscription && $organization->subscription->stripe_subscription_id) {
            // Get from Stripe subscription
            try {
                $stripe = app(StripeService::class);
                $stripeSubscription = $stripe->getSubscription($organization->subscription->stripe_subscription_id);
                return Carbon::createFromTimestamp($stripeSubscription->current_period_end);
            } catch (\Exception $e) {
                Log::warning('Could not fetch Stripe billing date, using default', [
                    'organization_id' => $organization->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Default: First day of next month
        return Carbon::now()->startOfMonth()->addMonth();
    }

    /**
     * Apply a pending tier change
     * Called by scheduled job on the scheduled date
     */
    public function applyTierChange(TierChangeLog $tierChangeLog): bool
    {
        DB::beginTransaction();

        try {
            $organization = $tierChangeLog->organization;
            $newTier = $tierChangeLog->toTier;

            // Update Stripe subscription
            if ($organization->subscription && $organization->subscription->stripe_subscription_id) {
                $stripe = app(StripeService::class);
                $stripe->updateSubscriptionPrice(
                    $organization->subscription->stripe_subscription_id,
                    $newTier->stripe_price_id
                );
            }

            // Update local subscription record
            if ($organization->subscription) {
                $organization->subscription->update([
                    'tier_id' => $newTier->id,
                    'pending_tier_id' => null,
                    'updated_at' => now(),
                ]);
            }

            // Mark tier change as applied
            $tierChangeLog->update([
                'status' => 'applied',
                'applied_at' => now(),
            ]);

            DB::commit();

            // Send confirmation email
            $this->sendTierAppliedNotification($organization, $tierChangeLog);

            Log::info('Tier change applied successfully', [
                'organization_id' => $organization->id,
                'tier_change_log_id' => $tierChangeLog->id,
                'new_tier_id' => $newTier->id,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error applying tier change', [
                'tier_change_log_id' => $tierChangeLog->id,
                'error' => $e->getMessage(),
            ]);

            // Mark as failed
            $tierChangeLog->update([
                'status' => 'failed',
                'notes' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send tier change notification email
     */
    protected function sendTierChangeNotification(Organization $organization, TierChangeLog $tierChangeLog): void
    {
        try {
            // Load relationships
            $tierChangeLog->load(['fromTier', 'toTier', 'organization']);

            // Send using Mailable class
            Mail::to($organization->email)
                ->send(new \App\Mail\TierChangeScheduled($tierChangeLog));

            $tierChangeLog->update(['notification_sent' => true]);

            Log::info('Tier change notification sent', [
                'organization_id' => $organization->id,
                'email' => $organization->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending tier change notification', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send tier applied confirmation email
     */
    protected function sendTierAppliedNotification(Organization $organization, TierChangeLog $tierChangeLog): void
    {
        try {
            // Load relationships
            $tierChangeLog->load(['fromTier', 'toTier', 'organization']);

            // Send using Mailable class
            Mail::to($organization->email)
                ->send(new \App\Mail\TierChangeApplied($tierChangeLog));

            Log::info('Tier applied notification sent', [
                'organization_id' => $organization->id,
                'email' => $organization->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending tier applied notification', [
                'organization_id' => $organization->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get tier progress for dashboard display
     */
    public function getTierProgress(Organization $organization): array
    {
        $total12Months = $this->calculate12MonthDonations($organization);
        $currentTier = $organization->subscription?->tier;
        $nextTier = $this->getNextTier($currentTier);

        $progress = 0;
        if ($currentTier && $nextTier) {
            $range = $nextTier->min_amount - $currentTier->min_amount;
            $current = $total12Months - $currentTier->min_amount;
            $progress = $range > 0 ? min(100, ($current / $range) * 100) : 0;
        }

        return [
            'total_12_months' => $total12Months,
            'current_tier' => $currentTier,
            'next_tier' => $nextTier,
            'progress_percentage' => round($progress, 1),
            'amount_to_next_tier' => $nextTier ? max(0, $nextTier->min_amount - $total12Months) : 0,
        ];
    }

    /**
     * Get next tier after current tier
     */
    protected function getNextTier(?SubscriptionTier $currentTier): ?SubscriptionTier
    {
        if (!$currentTier) {
            return SubscriptionTier::where('is_active', true)
                ->orderBy('min_amount', 'asc')
                ->first();
        }

        return SubscriptionTier::where('is_active', true)
            ->where('min_amount', '>', $currentTier->min_amount)
            ->orderBy('min_amount', 'asc')
            ->first();
    }
}
