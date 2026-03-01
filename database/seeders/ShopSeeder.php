<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Product Categories
        $categories = [
            [
                'name' => 'Donation Terminals',
                'slug' => 'donation-terminals',
                'description' => 'Professional donation terminals and kiosks for in-person fundraising',
                'sort_order' => 1,
            ],
            [
                'name' => 'Tablets & Displays',
                'slug' => 'tablets-displays',
                'description' => 'Tablets and digital displays for donation collection',
                'sort_order' => 2,
            ],
            [
                'name' => 'Card Readers',
                'slug' => 'card-readers',
                'description' => 'Mobile card readers and payment terminals',
                'sort_order' => 3,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Stands, cases, and other accessories',
                'sort_order' => 4,
            ],
            [
                'name' => 'Starter Kits',
                'slug' => 'starter-kits',
                'description' => 'Complete packages to get started with digital donations',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $categoryData) {
            ProductCategory::create($categoryData);
        }

        // Create Products
        $products = [
            // Donation Terminals
            [
                'category' => 'donation-terminals',
                'name' => 'Dayaa Pro Terminal',
                'slug' => 'dayaa-pro-terminal',
                'description' => 'Professional donation terminal with 10-inch touchscreen, NFC reader, and built-in receipt printer. Perfect for high-traffic locations.',
                'specifications' => [
                    'Display' => '10-inch HD touchscreen',
                    'Connectivity' => 'WiFi, 4G LTE, Ethernet',
                    'Payment Methods' => 'Contactless, Chip & PIN, Mobile Wallets',
                    'Printer' => 'Built-in thermal receipt printer',
                    'Dimensions' => '30cm x 20cm x 15cm',
                ],
                'price' => 599.00,
                'compare_price' => 699.00,
                'sku' => 'DPT-001',
                'quantity' => 25,
                'is_featured' => true,
            ],
            [
                'category' => 'donation-terminals',
                'name' => 'Dayaa Compact Kiosk',
                'slug' => 'dayaa-compact-kiosk',
                'description' => 'Space-saving donation kiosk with 7-inch display. Ideal for smaller venues and events.',
                'specifications' => [
                    'Display' => '7-inch touchscreen',
                    'Connectivity' => 'WiFi, Bluetooth',
                    'Payment Methods' => 'Contactless, Mobile Wallets',
                    'Dimensions' => '20cm x 15cm x 10cm',
                ],
                'price' => 349.00,
                'sku' => 'DCK-001',
                'quantity' => 40,
                'is_featured' => false,
            ],

            // Tablets & Displays
            [
                'category' => 'tablets-displays',
                'name' => 'Android Donation Tablet',
                'slug' => 'android-donation-tablet',
                'description' => '10-inch Android tablet pre-configured with Dayaa donation app. Includes protective case and stand.',
                'specifications' => [
                    'Display' => '10.1-inch Full HD',
                    'OS' => 'Android 13',
                    'Storage' => '64GB',
                    'RAM' => '4GB',
                    'Battery' => 'Up to 10 hours',
                ],
                'price' => 299.00,
                'sku' => 'ADT-001',
                'quantity' => 50,
                'is_featured' => true,
            ],
            [
                'category' => 'tablets-displays',
                'name' => 'iPad Donation Kit',
                'slug' => 'ipad-donation-kit',
                'description' => 'iPad with Dayaa app pre-installed, protective case, and desk stand.',
                'specifications' => [
                    'Display' => '10.2-inch Retina',
                    'OS' => 'iPadOS',
                    'Storage' => '64GB',
                    'Includes' => 'iPad, Case, Stand, Stylus',
                ],
                'price' => 449.00,
                'sku' => 'IDK-001',
                'quantity' => 30,
                'is_featured' => true,
            ],

            // Card Readers
            [
                'category' => 'card-readers',
                'name' => 'SumUp Card Reader',
                'slug' => 'sumup-card-reader',
                'description' => 'Portable card reader with contactless payment support. Works with smartphones and tablets.',
                'specifications' => [
                    'Connectivity' => 'Bluetooth',
                    'Battery Life' => '500 transactions per charge',
                    'Payment Methods' => 'Contactless, Chip, Swipe',
                    'Compatibility' => 'iOS and Android',
                ],
                'price' => 79.00,
                'sku' => 'SCR-001',
                'quantity' => 100,
                'is_featured' => false,
            ],
            [
                'category' => 'card-readers',
                'name' => 'Dayaa Mobile Reader Pro',
                'slug' => 'dayaa-mobile-reader-pro',
                'description' => 'Advanced mobile card reader with NFC, EMV chip, and magnetic stripe support.',
                'specifications' => [
                    'Connectivity' => 'Bluetooth 5.0',
                    'Battery Life' => '8 hours continuous use',
                    'Payment Methods' => 'All major cards, mobile wallets',
                ],
                'price' => 129.00,
                'compare_price' => 149.00,
                'sku' => 'DMR-001',
                'quantity' => 75,
                'is_featured' => false,
            ],

            // Accessories
            [
                'category' => 'accessories',
                'name' => 'Tablet Floor Stand',
                'slug' => 'tablet-floor-stand',
                'description' => 'Adjustable floor stand for tablets. Heavy-weighted base for stability.',
                'specifications' => [
                    'Height' => 'Adjustable 90cm - 140cm',
                    'Compatibility' => 'Tablets 7-13 inches',
                    'Material' => 'Aluminum alloy',
                    'Base' => 'Weighted steel',
                ],
                'price' => 89.00,
                'sku' => 'TFS-001',
                'quantity' => 60,
                'is_featured' => false,
            ],
            [
                'category' => 'accessories',
                'name' => 'Protective Tablet Case',
                'slug' => 'protective-tablet-case',
                'description' => 'Rugged protective case with hand strap. Suitable for events and outdoor use.',
                'specifications' => [
                    'Material' => 'Shock-absorbent silicone',
                    'Compatibility' => '10-inch tablets',
                    'Features' => 'Hand strap, Kickstand',
                ],
                'price' => 39.00,
                'sku' => 'PTC-001',
                'quantity' => 150,
                'is_featured' => false,
            ],

            // Starter Kits
            [
                'category' => 'starter-kits',
                'name' => 'Small Organization Starter Kit',
                'slug' => 'small-organization-starter-kit',
                'description' => 'Everything you need to start accepting digital donations. Includes tablet, card reader, stand, and 1-year subscription.',
                'specifications' => [
                    'Includes' => 'Android Tablet, Card Reader, Stand, Case',
                    'Subscription' => '1 year Starter plan included',
                    'Setup' => 'Pre-configured and ready to use',
                ],
                'price' => 449.00,
                'compare_price' => 529.00,
                'sku' => 'SOSK-001',
                'quantity' => 35,
                'is_featured' => true,
            ],
            [
                'category' => 'starter-kits',
                'name' => 'Professional Organization Kit',
                'slug' => 'professional-organization-kit',
                'description' => 'Complete professional setup with Pro Terminal, mobile reader, and accessories. Includes 1-year Professional subscription.',
                'specifications' => [
                    'Includes' => 'Pro Terminal, Mobile Reader, 2x Stands, Accessories',
                    'Subscription' => '1 year Professional plan included',
                    'Support' => 'Priority setup and training',
                ],
                'price' => 999.00,
                'compare_price' => 1199.00,
                'sku' => 'POSK-001',
                'quantity' => 20,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            $category = ProductCategory::where('slug', $productData['category'])->first();

            $product = Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => $productData['slug'],
                'description' => $productData['description'],
                'specifications' => $productData['specifications'],
                'price' => $productData['price'],
                'compare_price' => $productData['compare_price'] ?? null,
                'sku' => $productData['sku'],
                'quantity' => $productData['quantity'],
                'is_active' => true,
                'is_featured' => $productData['is_featured'],
            ]);

            // Create a placeholder image for each product
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'https://via.placeholder.com/600x400/0F69F3/ffffff?text=' . urlencode($product->name),
                'alt_text' => $product->name,
                'is_primary' => true,
                'sort_order' => 1,
            ]);
        }

        $this->command->info('Shop seeded successfully!');
        $this->command->info('Created ' . ProductCategory::count() . ' categories');
        $this->command->info('Created ' . Product::count() . ' products');
    }
}
