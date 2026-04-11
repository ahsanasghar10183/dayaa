<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\TierChangeLog;
use App\Services\SubscriptionTierService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApplyPendingTierChanges implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     * Runs daily to apply any pending tier changes that are due
     */
    public function handle(SubscriptionTierService $tierService): void
    {
        $today = Carbon::today();

        // Find all pending tier changes that are due today or earlier
        $pendingChanges = TierChangeLog::where('status', 'pending')
            ->whereDate('scheduled_for', '<=', $today)
            ->with(['organization', 'toTier'])
            ->get();

        Log::info('Processing pending tier changes', [
            'count' => $pendingChanges->count(),
            'date' => $today->toDateString(),
        ]);

        $successCount = 0;
        $failureCount = 0;

        foreach ($pendingChanges as $tierChange) {
            try {
                $success = $tierService->applyTierChange($tierChange);

                if ($success) {
                    $successCount++;
                    Log::info('Tier change applied successfully', [
                        'tier_change_id' => $tierChange->id,
                        'organization_id' => $tierChange->organization_id,
                    ]);
                } else {
                    $failureCount++;
                }
            } catch (\Exception $e) {
                $failureCount++;
                Log::error('Exception applying tier change', [
                    'tier_change_id' => $tierChange->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Tier changes processing complete', [
            'total' => $pendingChanges->count(),
            'success' => $successCount,
            'failed' => $failureCount,
        ]);
    }
}
