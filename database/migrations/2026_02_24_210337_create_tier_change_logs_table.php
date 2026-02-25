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
        Schema::create('tier_change_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_tier_id')->nullable()->constrained('subscription_tiers')->onDelete('set null');
            $table->foreignId('to_tier_id')->constrained('subscription_tiers')->onDelete('cascade');
            $table->enum('triggered_by', ['donation', 'manual', 'scheduled'])->default('donation');
            $table->enum('status', ['pending', 'applied', 'cancelled'])->default('pending');
            $table->decimal('donation_total_12m', 12, 2)->default(0); // 12-month total that triggered the change
            $table->timestamp('scheduled_date')->nullable(); // When the change will be applied
            $table->timestamp('applied_at')->nullable(); // When the change was actually applied
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('organization_id');
            $table->index(['status', 'scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tier_change_logs');
    }
};
