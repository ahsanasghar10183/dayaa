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
        Schema::create('subscription_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Free", "Tier 1", "Tier 2", etc.
            $table->decimal('min_amount', 12, 2)->default(0); // Minimum fundraising amount
            $table->decimal('max_amount', 12, 2)->nullable(); // Maximum fundraising amount (null = unlimited)
            $table->decimal('monthly_fee', 8, 2)->default(0); // Monthly subscription fee
            $table->string('stripe_price_id')->nullable(); // Stripe Price ID
            $table->json('features')->nullable(); // Array of feature descriptions
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['min_amount', 'max_amount']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_tiers');
    }
};
