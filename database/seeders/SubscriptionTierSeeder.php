<?php

namespace Database\Seeders;

use App\Models\SubscriptionTier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Free',
                'min_amount' => 0,
                'max_amount' => 1000,
                'monthly_fee' => 0,
                'stripe_price_id' => null,
                'features' => json_encode([
                    '1 active campaign',
                    '1 paired device',
                    'Basic analytics',
                    'Email support',
                ]),
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Tier 1',
                'min_amount' => 1000,
                'max_amount' => 5000,
                'monthly_fee' => 15,
                'stripe_price_id' => null, // Will be set when Stripe integration is configured
                'features' => json_encode([
                    '2 active campaigns',
                    '2 paired devices',
                    'Basic analytics',
                    'CSV export',
                    'Email support',
                ]),
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Tier 2',
                'min_amount' => 5000,
                'max_amount' => 25000,
                'monthly_fee' => 35,
                'stripe_price_id' => null,
                'features' => json_encode([
                    '3 active campaigns',
                    '3 paired devices',
                    'Advanced analytics',
                    'CSV export',
                    'Priority support',
                ]),
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Tier 3',
                'min_amount' => 25000,
                'max_amount' => 60000,
                'monthly_fee' => 70,
                'stripe_price_id' => null,
                'features' => json_encode([
                    '5 active campaigns',
                    '5 paired devices',
                    'Advanced analytics & charts',
                    'CSV & PDF export',
                    'Priority support',
                    'Custom branding',
                ]),
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Tier 4',
                'min_amount' => 60000,
                'max_amount' => 125000,
                'monthly_fee' => 120,
                'stripe_price_id' => null,
                'features' => json_encode([
                    '10 active campaigns',
                    '8 paired devices',
                    'Advanced analytics & real-time reports',
                    'All export formats',
                    'Priority support',
                    'Custom branding',
                    'API access',
                ]),
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Tier 5',
                'min_amount' => 125000,
                'max_amount' => 250000,
                'monthly_fee' => 180,
                'stripe_price_id' => null,
                'features' => json_encode([
                    'Unlimited campaigns',
                    '12 paired devices',
                    'Advanced analytics & real-time reports',
                    'All export formats',
                    '24/7 Priority support',
                    'Custom branding',
                    'API access',
                    'Dedicated account manager',
                ]),
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Tier 6',
                'min_amount' => 250000,
                'max_amount' => 425000,
                'monthly_fee' => 230,
                'stripe_price_id' => null,
                'features' => json_encode([
                    'Unlimited campaigns',
                    '15 paired devices',
                    'Advanced analytics & real-time reports',
                    'All export formats',
                    '24/7 Priority support',
                    'Custom branding',
                    'API access',
                    'Dedicated account manager',
                    'White-label options',
                ]),
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Tier 7',
                'min_amount' => 425000,
                'max_amount' => 650000,
                'monthly_fee' => 270,
                'stripe_price_id' => null,
                'features' => json_encode([
                    'Unlimited campaigns',
                    '20 paired devices',
                    'Advanced analytics & real-time reports',
                    'All export formats',
                    '24/7 Priority support',
                    'Custom branding',
                    'API access',
                    'Dedicated account manager',
                    'White-label options',
                    'Custom integrations',
                ]),
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Tier 8',
                'min_amount' => 650000,
                'max_amount' => 1250000,
                'monthly_fee' => 320,
                'stripe_price_id' => null,
                'features' => json_encode([
                    'Unlimited campaigns',
                    'Unlimited paired devices',
                    'Advanced analytics & real-time reports',
                    'All export formats',
                    '24/7 Priority support',
                    'Custom branding',
                    'API access',
                    'Dedicated account manager',
                    'White-label options',
                    'Custom integrations',
                    'On-premise deployment option',
                ]),
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Enterprise',
                'min_amount' => 1250000,
                'max_amount' => null, // Unlimited
                'monthly_fee' => 0, // Custom pricing
                'stripe_price_id' => null,
                'features' => json_encode([
                    'Everything in Tier 8',
                    'Custom pricing',
                    'Dedicated infrastructure',
                    'SLA guarantee',
                    'Custom features development',
                ]),
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($tiers as $tier) {
            SubscriptionTier::updateOrCreate(
                ['name' => $tier['name']],
                $tier
            );
        }

        $this->command->info('Subscription tiers seeded successfully!');
    }
}
