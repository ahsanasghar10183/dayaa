<?php

namespace App\Console\Commands;

use App\Services\SubscriptionTierService;
use Illuminate\Console\Command;

class RecalculateTiers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tiers:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate subscription tiers for all organizations based on 12-month donation totals';

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
        $this->info('Starting tier recalculation for all organizations...');

        $results = $this->tierService->recalculateAllOrganizationTiers();

        $this->info("Recalculation complete!");
        $this->line("Total organizations: {$results['total']}");
        $this->line("Tier changes scheduled: {$results['changes_scheduled']}");
        $this->line("No change needed: {$results['no_change']}");

        return Command::SUCCESS;
    }
}
