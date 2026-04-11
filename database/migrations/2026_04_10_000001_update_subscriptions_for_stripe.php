<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Stripe-related fields
            $table->string('stripe_customer_id')->nullable()->after('organization_id');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
            $table->string('stripe_price_id')->nullable()->after('stripe_subscription_id');
            $table->string('stripe_status')->nullable()->after('stripe_price_id'); // active, past_due, canceled, etc.
            
            // Billing dates
            $table->timestamp('trial_ends_at')->nullable()->after('stripe_status');
            $table->timestamp('current_period_start')->nullable()->after('trial_ends_at');
            $table->timestamp('current_period_end')->nullable()->after('current_period_start');
            $table->timestamp('canceled_at')->nullable()->after('current_period_end');
            
            // Tier management
            $table->foreignId('pending_tier_id')->nullable()->after('tier_id')->constrained('subscription_tiers')->nullOnDelete();
            
            // Payment method
            $table->string('payment_method_last4')->nullable()->after('pending_tier_id');
            $table->string('payment_method_brand')->nullable()->after('payment_method_last4'); // visa, mastercard, etc.
            
            // Indexes for better performance
            $table->index('stripe_customer_id');
            $table->index('stripe_subscription_id');
            $table->index('stripe_status');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['pending_tier_id']);
            $table->dropIndex(['stripe_customer_id']);
            $table->dropIndex(['stripe_subscription_id']);
            $table->dropIndex(['stripe_status']);
            
            $table->dropColumn([
                'stripe_customer_id',
                'stripe_subscription_id',
                'stripe_price_id',
                'stripe_status',
                'trial_ends_at',
                'current_period_start',
                'current_period_end',
                'canceled_at',
                'pending_tier_id',
                'payment_method_last4',
                'payment_method_brand',
            ]);
        });
    }
};
