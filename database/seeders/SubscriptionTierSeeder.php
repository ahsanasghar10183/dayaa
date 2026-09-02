<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionTier;
use Illuminate\Support\Facades\DB;

class SubscriptionTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * IMPORTANT: Before running this seeder, create the corresponding Price objects
     * in your Stripe Dashboard and update the stripe_price_id values below.
     */
    public function run(): void
    {
        // Clear existing tiers - disable foreign key checks first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('subscription_tiers')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tiers = [
            [
                'name' => 'Basic',
                'description' => 'For organizations raising €0 - €83 per year',
                'min_amount' => 0.00,
                'max_amount' => 83.00,
                'monthly_fee' => 15.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYB6FQiIRHgceVbdUVNg8e', // TODO: Replace with actual Stripe Price ID
                'features' => json_encode([
                    'Unlimited campaigns',
                    'Up to 5 devices',
                    'Basic analytics',
                    'Email support',
                ]),
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Premium',
                'description' => 'For organizations raising €84 - €667 per year',
                'min_amount' => 84.00,
                'max_amount' => 667.00,
                'monthly_fee' => 25.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYBfFQiIRHgceVAAwXi7oy',
                'features' => json_encode([
                    'Unlimited campaigns',
                    'Up to 10 devices',
                    'Advanced analytics',
                    'Priority email support',
                ]),
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'description' => 'For organizations raising €668 - €1,333 per year',
                'min_amount' => 668.00,
                'max_amount' => 1333.00,
                'monthly_fee' => 40.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYCXFQiIRHgceVfbBitxfu',
                'features' => json_encode([
                    'Unlimited campaigns',
                    'Up to 15 devices',
                    'Advanced analytics',
                    'Priority email support',
                    'Custom branding',
                ]),
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Premium',
                'description' => 'For organizations raising €1,334 - €2,500 per year',
                'min_amount' => 1334.00,
                'max_amount' => 2500.00,
                'monthly_fee' => 60.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYDDFQiIRHgceVg0GzCUTp',
                'features' => json_encode([
                    'Unlimited campaigns',
                    'Up to 25 devices',
                    'Advanced analytics & reports',
                    'Priority support',
                    'Custom branding',
                    'Dedicated account manager',
                ]),
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Premium',
                'description' => 'For organizations raising €2,501 - €5,000 per year',
                'min_amount' => 2501.00,
                'max_amount' => 5000.00,
                'monthly_fee' => 95.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYEXFQiIRHgceVkaGb6WzS',
                'features' => json_encode([
                    'Unlimited campaigns',
                    'Up to 50 devices',
                    'Advanced analytics & reports',
                    'Priority support',
                    'Custom branding',
                    'Dedicated account manager',
                    'API access',
                ]),
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Premium',
                'description' => 'For organizations raising €5,001 - €10,000 per year',
                'min_amount' => 5001.00,
                'max_amount' => 10000.00,
                'monthly_fee' => 140.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYExFQiIRHgceVs4COjdmq',
                'features' => json_encode([
                    'Unlimited campaigns',
                    'Unlimited devices',
                    'Advanced analytics & reports',
                    'Priority support (24/7)',
                    'Custom branding',
                    'Dedicated account manager',
                    'API access',
                    'White-label options',
                ]),
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Premium',
                'description' => 'For organizations raising €10,001 - €25,000 per year',
                'min_amount' => 10001.00,
                'max_amount' => 25000.00,
                'monthly_fee' => 200.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYFUFQiIRHgceVbcnLee6o',
                'features' => json_encode([
                    'Everything in previous tier',
                    'Custom integrations',
                    'Premium onboarding',
                    'Quarterly business reviews',
                ]),
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Premium',
                'description' => 'For organizations raising €25,001 - €50,000 per year',
                'min_amount' => 25001.00,
                'max_amount' => 50000.00,
                'monthly_fee' => 270.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYG2FQiIRHgceVu2KZf2y9',
                'features' => json_encode([
                    'Everything in previous tier',
                    'Enterprise SLA',
                    'Custom development hours',
                    'Strategic consulting',
                ]),
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Premium',
                'description' => 'For organizations raising €50,001 - €100,000 per year',
                'min_amount' => 50001.00,
                'max_amount' => 100000.00,
                'monthly_fee' => 380.00,
                'currency' => 'EUR',
                'stripe_price_id' => null, // TODO: Replace with actual Stripe Price ID
                'features' => json_encode([
                    'Everything in previous tier',
                    'Dedicated infrastructure',
                    'Full white-label solution',
                    'Enterprise SLA with 99.9% uptime',
                ]),
                'is_active' => true,
                'sort_order' => 9,
            ],
        ];

        foreach ($tiers as $tier) {
            SubscriptionTier::create($tier);
        }

        $this->command->info('✅ Successfully seeded 9 subscription tiers');
        $this->command->warn('⚠️  IMPORTANT: Update stripe_price_id values with actual Stripe Price IDs');
    }
}
