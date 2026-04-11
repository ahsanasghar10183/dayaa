<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('subscriptions', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->after('organization_id');
            }
            if (!Schema::hasColumn('subscriptions', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
            }
            if (!Schema::hasColumn('subscriptions', 'stripe_price_id')) {
                $table->string('stripe_price_id')->nullable()->after('stripe_subscription_id');
            }
            if (!Schema::hasColumn('subscriptions', 'stripe_status')) {
                $table->string('stripe_status')->nullable()->after('stripe_price_id');
            }

            // Billing dates
            if (!Schema::hasColumn('subscriptions', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('stripe_status');
            }
            if (!Schema::hasColumn('subscriptions', 'current_period_start')) {
                $table->timestamp('current_period_start')->nullable()->after('trial_ends_at');
            }
            if (!Schema::hasColumn('subscriptions', 'current_period_end')) {
                $table->timestamp('current_period_end')->nullable()->after('current_period_start');
            }
            if (!Schema::hasColumn('subscriptions', 'canceled_at')) {
                $table->timestamp('canceled_at')->nullable()->after('current_period_end');
            }

            // Tier management
            if (!Schema::hasColumn('subscriptions', 'pending_tier_id')) {
                $table->foreignId('pending_tier_id')->nullable()->after('tier_id')->constrained('subscription_tiers')->nullOnDelete();
            }

            // Payment method
            if (!Schema::hasColumn('subscriptions', 'payment_method_last4')) {
                $table->string('payment_method_last4')->nullable()->after('pending_tier_id');
            }
            if (!Schema::hasColumn('subscriptions', 'payment_method_brand')) {
                $table->string('payment_method_brand')->nullable()->after('payment_method_last4');
            }
        });

        // Add indexes separately (won't fail if they exist)
        Schema::table('subscriptions', function (Blueprint $table) {
            try {
                if (!Schema::hasIndex('subscriptions', 'subscriptions_stripe_customer_id_index', 'stripe_customer_id')) {
                    $table->index('stripe_customer_id');
                }
            } catch (\Exception $e) {
                // Index might already exist
            }

            try {
                if (!Schema::hasIndex('subscriptions', 'subscriptions_stripe_subscription_id_index', 'stripe_subscription_id')) {
                    $table->index('stripe_subscription_id');
                }
            } catch (\Exception $e) {
                // Index might already exist
            }

            try {
                if (!Schema::hasIndex('subscriptions', 'subscriptions_stripe_status_index', 'stripe_status')) {
                    $table->index('stripe_status');
                }
            } catch (\Exception $e) {
                // Index might already exist
            }
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
