<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tier_change_logs', function (Blueprint $table) {
            // Add scheduled_for if not exists
            if (!Schema::hasColumn('tier_change_logs', 'scheduled_for')) {
                $table->timestamp('scheduled_for')->nullable()->after('triggered_at');
            }
            
            // Add notification_sent if not exists
            if (!Schema::hasColumn('tier_change_logs', 'notification_sent')) {
                $table->boolean('notification_sent')->default(false)->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tier_change_logs', function (Blueprint $table) {
            $table->dropColumn(['scheduled_for', 'notification_sent']);
        });
    }
};
