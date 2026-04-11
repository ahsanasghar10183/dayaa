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
        // Clear existing tiers
        DB::table('subscription_tiers')->truncate();

        $tiers = [
            [
                'name' => 'Tier 1',
                'description' => 'For organizations raising €1,000 - €10,000 per year',
                'min_amount' => 1000.00,
                'max_amount' => 10000.00,
                'monthly_fee' => 10.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_tier1_REPLACE_ME', // TODO: Replace with actual Stripe Price ID
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
                'name' => 'Tier 2',
                'description' => 'For organizations raising €10,000 - €20,000 per year',
                'min_amount' => 10000.00,
                'max_amount' => 20000.00,
                'monthly_fee' => 20.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_tier2_REPLACE_ME',
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
                'name' => 'Tier 3',
                'description' => 'For organizations raising €20,000 - €30,000 per year',
                'min_amount' => 20000.00,
                'max_amount' => 30000.00,
                'monthly_fee' => 30.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_tier3_REPLACE_ME',
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
                'name' => 'Tier 4',
                'description' => 'For organizations raising €30,000 - €60,000 per year',
                'min_amount' => 30000.00,
                'max_amount' => 60000.00,
                'monthly_fee' => 60.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_tier4_REPLACE_ME',
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
                'name' => 'Tier 5',
                'description' => 'For organizations raising €60,000 - €125,000 per year',
                'min_amount' => 60000.00,
                'max_amount' => 125000.00,
                'monthly_fee' => 100.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_tier5_REPLACE_ME',
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
                'name' => 'Tier 6',
                'description' => 'For organizations raising €125,000 - €320,000 per year',
                'min_amount' => 125000.00,
                'max_amount' => 320000.00,
                'monthly_fee' => 160.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_tier6_REPLACE_ME',
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
                'name' => 'Tier 7',
                'description' => 'For organizations raising €320,000 - €650,000 per year',
                'min_amount' => 320000.00,
                'max_amount' => 650000.00,
                'monthly_fee' => 240.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_tier7_REPLACE_ME',
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
                'name' => 'Tier 8',
                'description' => 'For organizations raising €650,000 - €1,250,000 per year',
                'min_amount' => 650000.00,
                'max_amount' => 1250000.00,
                'monthly_fee' => 320.00,
                'currency' => 'EUR',
                'stripe_price_id' => 'price_tier8_REPLACE_ME',
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
                'name' => 'Enterprise',
                'description' => 'For organizations raising over €1,250,000 per year',
                'min_amount' => 1250000.00,
                'max_amount' => null, // No upper limit
                'monthly_fee' => null, // Custom pricing
                'currency' => 'EUR',
                'stripe_price_id' => null, // Handle manually
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
