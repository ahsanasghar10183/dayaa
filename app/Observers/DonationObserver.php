<?php

namespace App\Observers;

use App\Models\Donation;
use App\Services\SubscriptionTierService;

class DonationObserver
{
    protected SubscriptionTierService $tierService;

    public function __construct(SubscriptionTierService $tierService)
    {
        $this->tierService = $tierService;
    }

    /**
     * Handle the Donation "created" event.
     */
    public function created(Donation $donation): void
    {
        // Only check tier if donation is completed
        if ($donation->payment_status === 'completed') {
            $this->checkTier($donation);
        }
    }

    /**
     * Handle the Donation "updated" event.
     */
    public function updated(Donation $donation): void
    {
        // Check tier when payment status changes to completed
        if ($donation->isDirty('payment_status') && $donation->payment_status === 'completed') {
            $this->checkTier($donation);
        }
    }

    /**
     * Check and schedule tier change if needed
     */
    protected function checkTier(Donation $donation): void
    {
        try {
            $tierChange = $this->tierService->checkTierAfterDonation($donation);

            if ($tierChange) {
                \Log::info('Tier change scheduled', [
                    'organization_id' => $donation->organization_id,
                    'from_tier' => $tierChange->from_tier_id,
                    'to_tier' => $tierChange->to_tier_id,
                    'donation_total_12m' => $tierChange->donation_total_12m,
                    'scheduled_date' => $tierChange->scheduled_date,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error checking tier after donation', [
                'donation_id' => $donation->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
