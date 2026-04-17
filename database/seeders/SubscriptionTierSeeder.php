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
                'name' => 'DAYAA Tier 1',
                'description' => 'For organizations raising €1,000 - €10,000 per year',
                'min_amount' => 1000.00,
                'max_amount' => 10000.00,
                'monthly_fee' => 10.00,
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
                'name' => 'DAYAA Tier 2',
                'description' => 'For organizations raising €10,000 - €30,000 per year',
                'min_amount' => 10000.00,
                'max_amount' => 30000.00,
                'monthly_fee' => 20.00,
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
                'name' => 'DAYAA Tier 3',
                'description' => 'For organizations raising €30,000 - €60,000 per year',
                'min_amount' => 30000.00,
                'max_amount' => 60000.00,
                'monthly_fee' => 30.00,
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
                'name' => 'DAYAA Tier 4',
                'description' => 'For organizations raising €60,000 - €100,000 per year',
                'min_amount' => 60000.00,
                'max_amount' => 100000.00,
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
                'name' => 'DAYAA Tier 5',
                'description' => 'For organizations raising €100,000 - €160,000 per year',
                'min_amount' => 100000.00,
                'max_amount' => 160000.00,
                'monthly_fee' => 100.00,
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
                'name' => 'DAYAA Tier 6',
                'description' => 'For organizations raising €160,000 - €240,000 per year',
                'min_amount' => 160000.00,
                'max_amount' => 240000.00,
                'monthly_fee' => 160.00,
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
                'name' => 'DAYAA Tier 7',
                'description' => 'For organizations raising €240,000 - €320,000 per year',
                'min_amount' => 240000.00,
                'max_amount' => 320000.00,
                'monthly_fee' => 240.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYFUFQiIRHgceVbcnLee6o',
                'features' => json_encode([
                    'Everything in Tier 6',
                    'Custom integrations',
                    'Premium onboarding',
                    'Quarterly business reviews',
                ]),
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'DAYAA Tier 8',
                'description' => 'For organizations raising €320,000+ per year',
                'min_amount' => 320000.00,
                'max_amount' => null,
                'monthly_fee' => 320.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_1TLYG2FQiIRHgceVu2KZf2y9',
                'features' => json_encode([
                    'Everything in Tier 7',
                    'Enterprise SLA',
                    'Custom development hours',
                    'Strategic consulting',
                ]),
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'DAYAA Enterprise',
                'description' => 'For enterprise organizations with custom requirements',
                'min_amount' => 999999999.00,
                'max_amount' => null,
                'monthly_fee' => null,
                'currency' => 'EUR',
                'stripe_price_id' => null,
                'features' => json_encode([
                    'Custom enterprise pricing',
                    'Dedicated infrastructure',
                    'Full white-label solution',
                    'Enterprise SLA with 99.9% uptime',
                    'Custom feature development',
                    'Strategic partnership',
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
