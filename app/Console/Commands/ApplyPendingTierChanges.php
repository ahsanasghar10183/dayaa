<?php

namespace App\Console\Commands;

use App\Services\SubscriptionTierService;
use Illuminate\Console\Command;

class ApplyPendingTierChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tiers:apply-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply all pending tier changes that are scheduled for today or earlier';

    protected SubscriptionTierService $tierService;

    public function __construct(SubscriptionTierService $tierService)
    {
        parent::__construct();
        $this->tierService = $tierService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing pending tier changes...');

        $processed = $this->tierService->processPendingTierChanges();

        $this->info("Tier changes applied: {$processed}");

        return Command::SUCCESS;
    }
}
