<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\Campaign;
use App\Models\Device;
use App\Models\Donation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@dayaa.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'language' => 'en',
                'email_verified_at' => now(),
            ]
        );

        // Create Organization Admin 1 (Approved)
        $orgAdmin1 = User::firstOrCreate(
            ['email' => 'org1@dayaa.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'organization_admin',
                'language' => 'en',
                'email_verified_at' => now(),
            ]
        );

        $organization1 = Organization::create([
            'user_id' => $orgAdmin1->id,
            'name' => 'Red Cross Berlin',
            'description' => 'We provide humanitarian aid and disaster relief across Berlin and surrounding areas.',
            'contact_person' => 'John Doe',
            'phone' => '+49 30 12345678',
            'address' => 'Hauptstraße 123, 10115 Berlin, Germany',
            'charity_number' => 'CHR-2024-001',
            'tax_id' => 'DE123456789',
            'website' => 'https://redcross-berlin.org',
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => $superAdmin->id,
        ]);

        // Create subscription for organization 1
        Subscription::create([
            'organization_id' => $organization1->id,
            'plan' => 'premium',
            'price' => 10.00,
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
            'next_billing_date' => now()->addMonth(),
        ]);

        // Create campaigns for organization 1
        $campaign1 = Campaign::create([
            'organization_id' => $organization1->id,
            'name' => 'Winter Relief 2024',
            'description' => 'Help us provide warm clothes and hot meals to those in need this winter.',
            'status' => 'active',
            'start_date' => now()->subDays(30),
            'end_date' => now()->addMonths(2),
            'design_settings' => [
                'primary_color' => '#1163F0',
                'secondary_color' => '#1707B2',
            ],
            'content_settings' => [
                'title' => 'Support Winter Relief',
                'subtitle' => 'Every donation makes a difference',
                'call_to_action' => 'Donate Now',
                'thank_you_message' => 'Thank you for your generous donation!',
            ],
            'amount_settings' => [
                'preset_amounts' => [5, 10, 20, 50, 100],
                'min_amount' => 5,
                'max_amount' => 1000,
                'allow_custom' => true,
            ],
        ]);

        $campaign2 = Campaign::create([
            'organization_id' => $organization1->id,
            'name' => 'Emergency Medical Fund',
            'description' => 'Supporting emergency medical services and first responders.',
            'status' => 'active',
            'start_date' => now()->subDays(15),
            'design_settings' => [
                'primary_color' => '#EF4444',
                'secondary_color' => '#DC2626',
            ],
            'content_settings' => [
                'title' => 'Emergency Medical Fund',
                'subtitle' => 'Save lives with your contribution',
                'call_to_action' => 'Contribute',
                'thank_you_message' => 'Your contribution helps save lives!',
            ],
            'amount_settings' => [
                'preset_amounts' => [10, 25, 50, 100],
                'min_amount' => 10,
                'allow_custom' => true,
            ],
        ]);

        // Create devices for organization 1
        $device1 = Device::create([
            'organization_id' => $organization1->id,
            'device_id' => 'DEV-TABLET-001',
            'name' => 'Main Office Tablet',
            'status' => 'online',
            'last_active' => now(),
        ]);

        $device2 = Device::create([
            'organization_id' => $organization1->id,
            'device_id' => 'DEV-TABLET-002',
            'name' => 'Event Booth Tablet',
            'status' => 'offline',
            'last_active' => now()->subHours(3),
        ]);

        // Assign devices to campaigns
        $campaign1->devices()->attach([$device1->id, $device2->id]);
        $campaign2->devices()->attach([$device1->id]);

        // Create sample donations for organization 1
        for ($i = 0; $i < 15; $i++) {
            Donation::create([
                'organization_id' => $organization1->id,
                'campaign_id' => $campaign1->id,
                'device_id' => $device1->id,
                'amount' => $faker->randomElement([5, 10, 20, 50, 100]),
                'receipt_number' => 'RCP-' . date('Ymd') . '-' . strtoupper($faker->bothify('????####')),
                'donor_name' => $faker->name(),
                'donor_email' => $faker->safeEmail(),
                'payment_method' => 'card',
                'payment_status' => 'success',
                'transaction_id' => 'TXN-' . $faker->uuid(),
                'sumup_transaction_id' => 'SUMUP-' . $faker->uuid(),
                'sumup_fee' => 0.50,
                'net_amount' => $faker->randomElement([4.50, 9.50, 19.50, 49.50, 99.50]),
                'currency' => 'EUR',
                'ip_address' => $faker->ipv4(),
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }

        // Create Organization Admin 2 (Pending)
        $orgAdmin2 = User::firstOrCreate(
            ['email' => 'org2@dayaa.com'],
            [
                'name' => 'Maria Schmidt',
                'password' => Hash::make('password'),
                'role' => 'organization_admin',
                'language' => 'de',
                'email_verified_at' => now(),
            ]
        );

        Organization::create([
            'user_id' => $orgAdmin2->id,
            'name' => 'Tierheim München',
            'description' => 'Animal shelter dedicated to rescuing and rehoming abandoned animals.',
            'contact_person' => 'Maria Schmidt',
            'phone' => '+49 89 98765432',
            'address' => 'Tierstraße 45, 80331 München, Germany',
            'charity_number' => 'CHR-2024-002',
            'website' => 'https://tierheim-muenchen.de',
            'status' => 'pending',
        ]);

        // Create Organization Admin 3 (Rejected)
        $orgAdmin3 = User::firstOrCreate(
            ['email' => 'org3@dayaa.com'],
            [
                'name' => 'Hans Weber',
                'password' => Hash::make('password'),
                'role' => 'organization_admin',
                'language' => 'de',
                'email_verified_at' => now(),
            ]
        );

        Organization::create([
            'user_id' => $orgAdmin3->id,
            'name' => 'Test Organization',
            'description' => 'Invalid organization for testing purposes.',
            'contact_person' => 'Hans Weber',
            'phone' => '+49 123 456789',
            'address' => 'Test Street 1, 12345 Berlin',
            'status' => 'rejected',
            'rejection_reason' => 'Incomplete documentation provided. Please submit valid charity registration certificate.',
        ]);
    }
}

