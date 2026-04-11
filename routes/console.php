<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Tasks
// Apply pending tier changes daily at midnight
Schedule::job(new \App\Jobs\ApplyPendingTierChanges)->daily()->at('00:00');
