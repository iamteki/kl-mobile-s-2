<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Service;
use App\Models\Package;
use App\Models\Customer;
use App\Models\Inventory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@klmobile.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create test customer user
        $customerUser = User::create([
            'name' => 'John Doe',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create customer profile
        Customer::create([
            'user_id' => $customerUser->id,
            'phone' => '+60123456789',
            'address' => '123 Main Street, Kuala Lumpur',
            'company' => 'Test Company Sdn Bhd',
            'customer_type' => 'corporate',
        ]);

        // Create categories
        $categories = [
            [
                'name' => 'Sound Equipment',
                'slug' => 'sound-equipment',
                'icon' => 'fas fa-volume-up',
                'description' => 'Professional PA systems, speakers, mixers, and audio equipment for events of all sizes'
            ],
            [
                'name' => 'Lighting Equipment',
                'slug' => 'lighting',
                'icon' => 'fas fa-lightbulb',
                'description' => 'LED lights, spotlights, moving heads, and stage lighting solutions'
            ],
            [
                'name' => 'LED Screens',
                'slug' => 'led-screens',
                'icon' => 'fas fa-tv',
                'description' => 'Indoor and outdoor LED display screens for presentations and visuals'
            ],
            [
                'name' => 'DJ Equipment',
                'slug' => 'dj-equipment',
                'icon' => 'fas fa-headphones',
                'description' => 'Professional DJ controllers, turntables, and mixing equipment'
            ],
            [
                'name' => 'Backdrops',
                'slug' => 'backdrops',
                'icon' => 'fas fa-image',
                'description' => 'Event backdrops, pipe and drape systems for all occasions'
            ],
            [
                'name' => 'Tables & Chairs',
                'slug' => 'tables-chairs',
                'icon' => 'fas fa-chair',
                'description' => 'Event furniture including banquet tables, chairs, and cocktail tables'
            ],
            [
                'name' => 'Tents & Canopy',
                'slug' => 'tents-canopy',
                'icon' => 'fas fa-campground',
                'description' => 'Outdoor event tents, marquees, and weather protection'
            ],
            [
                'name' => 'Photo Booths',
                'slug' => 'photo-booths',
                'icon' => 'fas fa-camera',
                'description' => 'Interactive photo booths with instant printing and digital sharing'
            ],
        ];

        foreach ($categories as $index => $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'icon' => $categoryData['icon'],
                'description' => $categoryData['description'],
                'sort_order' => $index,
                'show_on_homepage' => true,
                'status' => 'active',
            ]);

            // Create products for each category
            $this->createProductsForCategory($category);
        }

        // Create services
        $this->createServices();

        // Create packages
        $this->createPackages();

        $this->command->info('Database seeded successfully!');
    }

    private function createProductsForCategory($category)
    {
        $productsByCategory = [
            'sound-equipment' => [
                [
                    'name' => 'JBL Professional PA System',
                    'brand' => 'JBL',
                    'base_price' => 15000,
                    'subcategory' => 'PA Systems',
                    'short_description' => 'Professional-grade PA system perfect for corporate events, weddings, and conferences. Features crystal-clear audio output with 1000W RMS power.',
                    'featured' => true,
                    'variations' => [
                        ['name' => '500W System', 'price' => 10000],
                        ['name' => '1000W System', 'price' => 15000],
                        ['name' => '1500W System', 'price' => 20000],
                    ]
                ],
                [
                    'name' => 'Yamaha MG16XU 16-Channel Mixer',
                    'brand' => 'Yamaha',
                    'base_price' => 8000,
                    'subcategory' => 'Mixers',
                    'short_description' => '16-channel mixing console with built-in effects and USB audio interface.',
                ],
                [
                    'name' => 'Shure BLX Wireless Microphone Set',
                    'brand' => 'Shure',
                    'base_price' => 5000,
                    'subcategory' => 'Microphones',
                    'short_description' => 'Professional wireless microphone system with reliable UHF performance.',
                ],
            ],
            'lighting' => [
                [
                    'name' => 'LED Par Light Set (12 Units)',
                    'brand' => 'Chauvet',
                    'base_price' => 8000,
                    'subcategory' => 'LED Lights',
                    'short_description' => 'RGB LED par lights with DMX control for vibrant stage lighting.',
                    'featured' => true,
                ],
                [
                    'name' => 'Moving Head Spot Light',
                    'brand' => 'Martin',
                    'base_price' => 12000,
                    'subcategory' => 'Moving Heads',
                    'short_description' => 'Professional moving head with gobo patterns and color wheels.',
                ],
                [
                    'name' => 'LED Uplighting Package',
                    'brand' => 'ADJ',
                    'base_price' => 6000,
                    'subcategory' => 'Uplighting',
                    'short_description' => 'Wireless LED uplights for ambient venue lighting.',
                ],
            ],
            'led-screens' => [
                [
                    'name' => 'P3.91 Indoor LED Wall (3x2m)',
                    'brand' => 'Absen',
                    'base_price' => 25000,
                    'short_description' => 'High-resolution indoor LED video wall perfect for presentations.',
                    'featured' => true,
                ],
                [
                    'name' => 'P4.81 Outdoor LED Screen',
                    'brand' => 'Novastar',
                    'base_price' => 30000,
                    'short_description' => 'Weather-resistant outdoor LED display for large events.',
                ],
            ],
            'dj-equipment' => [
                [
                    'name' => 'Pioneer DDJ-FLX6 Controller',
                    'brand' => 'Pioneer',
                    'base_price' => 12000,
                    'short_description' => '4-channel DJ controller with Serato DJ Pro compatibility.',
                    'featured' => true,
                ],
                [
                    'name' => 'Technics SL-1200 Turntables (Pair)',
                    'brand' => 'Technics',
                    'base_price' => 18000,
                    'short_description' => 'Industry-standard direct drive turntables for professional DJs.',
                ],
            ],
            'tables-chairs' => [
                [
                    'name' => 'Round Banquet Table (10 pax)',
                    'brand' => 'Generic',
                    'base_price' => 500,
                    'short_description' => 'Standard round banquet table with white tablecloth.',
                    'variations' => [
                        ['name' => '6 pax table', 'price' => 400],
                        ['name' => '8 pax table', 'price' => 450],
                        ['name' => '10 pax table', 'price' => 500],
                    ]
                ],
                [
                    'name' => 'Chiavari Chairs',
                    'brand' => 'Generic',
                    'base_price' => 150,
                    'short_description' => 'Elegant Chiavari chairs available in gold, silver, or white.',
                    'variations' => [
                        ['name' => 'Gold', 'price' => 150],
                        ['name' => 'Silver', 'price' => 150],
                        ['name' => 'White', 'price' => 150],
                    ]
                ],
            ],
        ];

        $products = $productsByCategory[$category->slug] ?? [];

        foreach ($products as $index => $productData) {
            $product = Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'sku' => strtoupper(substr($category->slug, 0, 3)) . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'brand' => $productData['brand'],
                'subcategory' => $productData['subcategory'] ?? null,
                'short_description' => $productData['short_description'],
                'base_price' => $productData['base_price'],
                'price_unit' => 'day',
                'min_quantity' => 1,
                'max_quantity' => 10,
                'available_quantity' => rand(5, 20),
                'featured' => $productData['featured'] ?? false,
                'status' => 'active',
                'included_items' => json_encode($this->getIncludedItems($category->slug)),
            ]);

            // Create inventory record
            Inventory::create([
                'product_id' => $product->id,
                'total_quantity' => $product->available_quantity,
                'available_quantity' => $product->available_quantity,
                'location' => 'Warehouse A',
                'status' => 'active',
            ]);

            // Create variations if any
            if (isset($productData['variations'])) {
                foreach ($productData['variations'] as $variationIndex => $variation) {
                    $productVariation = ProductVariation::create([
                        'product_id' => $product->id,
                        'name' => $variation['name'],
                        'sku' => $product->sku . '-V' . ($variationIndex + 1),
                        'price' => $variation['price'],
                        'available_quantity' => rand(3, 10),
                        'status' => 'active',
                    ]);

                    // Create inventory for variation
                    Inventory::create([
                        'product_id' => $product->id,
                        'product_variation_id' => $productVariation->id,
                        'total_quantity' => $productVariation->available_quantity,
                        'available_quantity' => $productVariation->available_quantity,
                        'location' => 'Warehouse A',
                        'status' => 'active',
                    ]);
                }
            }
        }
    }

    private function createServices()
    {
        $services = [
            // Entertainment
            [
                'name' => 'Professional DJs',
                'slug' => 'professional-djs',
                'category' => 'Entertainment',
                'starting_price' => 25000,
                'price_unit' => 'event',
                'features' => [
                    'Experienced club & event DJs',
                    'All music genres available',
                    'Professional DJ equipment included',
                    '4-8 hours performance'
                ],
                'badge' => 'Most Popular',
                'featured' => true,
            ],
            [
                'name' => 'Professional Emcees',
                'slug' => 'professional-emcees',
                'category' => 'Entertainment',
                'starting_price' => 20000,
                'price_unit' => 'event',
                'features' => [
                    'Bilingual emcees available',
                    'Corporate & wedding specialists',
                    'Professional attire included',
                    'Script coordination service'
                ],
            ],
            [
                'name' => 'Live Bands',
                'slug' => 'live-bands',
                'category' => 'Entertainment',
                'starting_price' => 60000,
                'price_unit' => 'event',
                'features' => [
                    '4-8 piece band configurations',
                    'Jazz, pop, rock, classical',
                    'Professional sound included',
                    '2-4 hours performance sets'
                ],
                'badge' => 'Premium',
            ],
            // Technical Crew
            [
                'name' => 'Sound Engineers',
                'slug' => 'sound-engineers',
                'category' => 'Technical Crew',
                'starting_price' => 15000,
                'price_unit' => 'day',
                'features' => [
                    'Professional audio mixing',
                    'Equipment setup & operation',
                    'Live sound management',
                    'Technical troubleshooting'
                ],
            ],
            [
                'name' => 'Lighting Engineers',
                'slug' => 'lighting-engineers',
                'category' => 'Technical Crew',
                'starting_price' => 18000,
                'price_unit' => 'day',
                'features' => [
                    'Professional lighting design',
                    'DMX programming & operation',
                    'Effect lighting coordination',
                    'Full event coverage'
                ],
            ],
            // Media Production
            [
                'name' => 'Videographers',
                'slug' => 'videographers',
                'category' => 'Media Production',
                'starting_price' => 40000,
                'price_unit' => 'event',
                'features' => [
                    '4K professional videography',
                    'Multi-camera coverage',
                    'Drone footage available',
                    'Same-day highlights option'
                ],
                'badge' => '4K Video',
            ],
            [
                'name' => 'Photographers',
                'slug' => 'photographers',
                'category' => 'Media Production',
                'starting_price' => 30000,
                'price_unit' => 'event',
                'features' => [
                    'Professional event photography',
                    'Candid & portrait shots',
                    'Instant photo sharing',
                    'Post-production editing'
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create([
                'name' => $serviceData['name'],
                'slug' => $serviceData['slug'],
                'category' => $serviceData['category'],
                'starting_price' => $serviceData['starting_price'],
                'price_unit' => $serviceData['price_unit'],
                'features' => json_encode($serviceData['features']),
                'badge' => $serviceData['badge'] ?? null,
                'featured' => $serviceData['featured'] ?? false,
                'status' => 'active',
            ]);
        }
    }

    private function createPackages()
    {
        $packages = [
            [
                'name' => 'Basic Package',
                'slug' => 'basic-package',
                'category' => 'Basic',
                'price' => 45000,
                'suitable_for' => '50-100 pax',
                'description' => 'Perfect for small events',
                'features' => [
                    'Basic Sound System',
                    '8 LED Par Lights',
                    '1 Wireless Microphone',
                    'Basic DJ Setup',
                    '4 Hours Service',
                    '1 Technician'
                ],
                'service_duration' => 4,
            ],
            [
                'name' => 'Professional Package',
                'slug' => 'professional-package',
                'category' => 'Professional',
                'price' => 85000,
                'suitable_for' => '100-300 pax',
                'description' => 'Ideal for corporate events',
                'features' => [
                    'Professional PA System',
                    'Stage Lighting Setup',
                    '2 Wireless Microphones',
                    'Professional DJ',
                    'LED Screen (2x1m)',
                    '8 Hours Service',
                    '2 Technicians'
                ],
                'badge' => 'Most Popular',
                'featured' => true,
                'service_duration' => 8,
            ],
            [
                'name' => 'Premium Package',
                'slug' => 'premium-package',
                'category' => 'Premium',
                'price' => 150000,
                'suitable_for' => '300+ pax',
                'description' => 'For large-scale events',
                'features' => [
                    'Line Array Sound System',
                    'Full Stage Lighting',
                    '4 Wireless Microphones',
                    'Professional DJ & Emcee',
                    'LED Wall (4x3m)',
                    'Special Effects',
                    'Full Day Service',
                    'Full Technical Team'
                ],
                'service_duration' => 12,
            ],
        ];

        foreach ($packages as $packageData) {
            Package::create([
                'name' => $packageData['name'],
                'slug' => $packageData['slug'],
                'category' => $packageData['category'],
                'price' => $packageData['price'],
                'suitable_for' => $packageData['suitable_for'],
                'description' => $packageData['description'],
                'features' => json_encode($packageData['features']),
                'badge' => $packageData['badge'] ?? null,
                'featured' => $packageData['featured'] ?? false,
                'service_duration' => $packageData['service_duration'],
                'status' => 'active',
            ]);
        }
    }

    private function getIncludedItems($categorySlug)
    {
        $includedItems = [
            'sound-equipment' => [
                '2x Professional Speakers',
                '2x Speaker Stands',
                'Mixing Console',
                'All Necessary Cables',
                'Power Distribution',
                'Setup & Testing'
            ],
            'lighting' => [
                'Complete Light Set',
                'DMX Controller',
                'Lighting Stands/Trusses',
                'Power & DMX Cables',
                'Safety Equipment',
                'Programming Service'
            ],
            'led-screens' => [
                'LED Panel Modules',
                'Processing Unit',
                'Mounting Structure',
                'Power Distribution',
                'Content Management',
                'Technical Support'
            ],
            'dj-equipment' => [
                'DJ Controller/Turntables',
                'DJ Mixer',
                'Headphones',
                'All Cables',
                'Laptop Stand',
                'Basic Lighting'
            ],
        ];

        return $includedItems[$categorySlug] ?? ['Equipment as described', 'Basic accessories', 'Setup guide'];
    }
}