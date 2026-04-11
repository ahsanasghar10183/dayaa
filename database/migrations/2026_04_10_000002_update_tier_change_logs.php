<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tier_change_logs', function (Blueprint $table) {
            // Check columns that exist in the table
            $columns = Schema::getColumnListing('tier_change_logs');

            // Add scheduled_for if not exists (table has scheduled_date, not triggered_at)
            if (!in_array('scheduled_for', $columns)) {
                // Use applied_at as reference since triggered_at doesn't exist
                if (in_array('applied_at', $columns)) {
                    $table->timestamp('scheduled_for')->nullable()->after('applied_at');
                } else {
                    $table->timestamp('scheduled_for')->nullable();
                }
            }

            // Add notification_sent if not exists
            if (!in_array('notification_sent', $columns)) {
                if (in_array('notes', $columns)) {
                    $table->boolean('notification_sent')->default(false)->after('notes');
                } else {
                    $table->boolean('notification_sent')->default(false);
                }
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
