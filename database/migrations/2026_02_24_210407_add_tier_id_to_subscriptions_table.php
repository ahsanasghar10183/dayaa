<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('tier_id')->nullable()->after('organization_id')->constrained('subscription_tiers')->onDelete('set null');
            // Keep 'plan' column for backwards compatibility, but make it nullable
            $table->enum('plan', ['basic', 'premium'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['tier_id']);
            $table->dropColumn('tier_id');
            $table->enum('plan', ['basic', 'premium'])->default('basic')->change();
        });
    }
};
