<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderPricing;
use App\Models\ServiceProviderMedia;
use Illuminate\Support\Str;

class ServiceCategoriesSeeder extends Seeder
{
    public function run()
    {
        // Create service categories
        $categories = [
            // Entertainment
            [
                'name' => 'Professional DJs',
                'slug' => 'professional-djs',
                'description' => 'Experienced DJs for all types of events',
                'icon' => 'fas fa-headphones',
                'parent_category' => 'entertainment',
                'providers' => [
                    [
                        'name' => 'Mike Johnson',
                        'stage_name' => 'DJ Mike',
                        'bio' => 'Professional DJ with 10+ years experience in clubs and events',
                        'base_price' => 25000,
                        'experience_level' => 'Professional',
                        'years_experience' => 10,
                        'languages' => ['English', 'Sinhala'],
                        'specialties' => ['House', 'Hip Hop', 'Commercial', 'Retro'],
                        'badge' => 'Top Rated',
                        'rating' => 4.8,
                        'total_bookings' => 150,
                        'featured' => true,
                    ],
                    [
                        'name' => 'Sarah Williams',
                        'stage_name' => 'DJ Sara',
                        'bio' => 'Specializing in weddings and corporate events',
                        'base_price' => 30000,
                        'experience_level' => 'Premium',
                        'years_experience' => 8,
                        'languages' => ['English', 'Tamil'],
                        'specialties' => ['Wedding', 'Corporate', 'Lounge', 'Bollywood'],
                        'rating' => 4.9,
                        'total_bookings' => 120,
                    ],
                    [
                        'name' => 'Alex Chen',
                        'stage_name' => 'DJ Fusion',
                        'bio' => 'Electronic music specialist',
                        'base_price' => 20000,
                        'experience_level' => 'Professional',
                        'years_experience' => 5,
                        'languages' => ['English'],
                        'specialties' => ['EDM', 'Techno', 'Progressive', 'Trance'],
                        'rating' => 4.5,
                        'total_bookings' => 80,
                    ],
                ]
            ],
            [
                'name' => 'Event Emcees',
                'slug' => 'event-emcees',
                'description' => 'Professional MCs for all occasions',
                'icon' => 'fas fa-microphone',
                'parent_category' => 'entertainment',
                'providers' => [
                    [
                        'name' => 'David Fernando',
                        'bio' => 'Bilingual MC with charismatic personality',
                        'base_price' => 20000,
                        'experience_level' => 'Professional',
                        'years_experience' => 12,
                        'languages' => ['English', 'Sinhala', 'Tamil'],
                        'specialties' => ['Weddings', 'Corporate Events', 'Award Shows'],
                        'badge' => 'Most Popular',
                        'rating' => 4.9,
                        'featured' => true,
                    ],
                ]
            ],
            [
                'name' => 'Live Bands',
                'slug' => 'live-bands',
                'description' => 'Professional bands for live performances',
                'icon' => 'fas fa-guitar',
                'parent_category' => 'entertainment',
                'providers' => [
                    [
                        'name' => 'The Groove Masters',
                        'bio' => '6-piece band specializing in contemporary and classic hits',
                        'base_price' => 50000,
                        'experience_level' => 'Premium',
                        'years_experience' => 15,
                        'languages' => ['English', 'Sinhala'],
                        'specialties' => ['Pop', 'Rock', 'Jazz', 'Soul'],
                        'equipment_provided' => true,
                        'rating' => 4.7,
                    ],
                ]
            ],
            
            // Technical Crew
            [
                'name' => 'Sound Engineers',
                'slug' => 'sound-engineers',
                'description' => 'Professional audio engineers for events',
                'icon' => 'fas fa-sliders-h',
                'parent_category' => 'technical-crew',
                'providers' => [
                    [
                        'name' => 'James Wilson',
                        'bio' => 'Certified sound engineer with expertise in live events',
                        'base_price' => 15000,
                        'price_unit' => 'day',
                        'experience_level' => 'Professional',
                        'years_experience' => 8,
                        'languages' => ['English'],
                        'specialties' => ['Live Mixing', 'System Setup', 'Recording'],
                        'rating' => 4.6,
                    ],
                ]
            ],
            [
                'name' => 'Lighting Technicians',
                'slug' => 'lighting-technicians',
                'description' => 'Expert lighting designers and operators',
                'icon' => 'fas fa-lightbulb',
                'parent_category' => 'technical-crew',
                'providers' => [
                    [
                        'name' => 'Kumar Perera',
                        'bio' => 'Creative lighting designer for events and shows',
                        'base_price' => 12000,
                        'price_unit' => 'day',
                        'experience_level' => 'Professional',
                        'years_experience' => 6,
                        'languages' => ['English', 'Sinhala'],
                        'specialties' => ['Stage Lighting', 'Architectural Lighting', 'Effects'],
                        'rating' => 4.5,
                    ],
                ]
            ],
            
            // Media Production
            [
                'name' => 'Photographers',
                'slug' => 'photographers',
                'description' => 'Professional event photographers',
                'icon' => 'fas fa-camera',
                'parent_category' => 'media-production',
                'providers' => [
                    [
                        'name' => 'Lisa Anderson',
                        'bio' => 'Award-winning photographer specializing in events',
                        'base_price' => 35000,
                        'experience_level' => 'Premium',
                        'years_experience' => 10,
                        'languages' => ['English'],
                        'specialties' => ['Weddings', 'Corporate', 'Fashion', 'Product'],
                        'equipment_owned' => ['Canon 5D Mark IV', 'Professional Lenses', 'Studio Lights'],
                        'badge' => 'Award Winner',
                        'rating' => 5.0,
                        'featured' => true,
                    ],
                    [
                        'name' => 'Raj Kumar',
                        'bio' => 'Creative photographer with unique perspective',
                        'base_price' => 25000,
                        'experience_level' => 'Professional',
                        'years_experience' => 7,
                        'languages' => ['English', 'Tamil'],
                        'specialties' => ['Events', 'Portraits', 'Documentary'],
                        'rating' => 4.7,
                    ],
                ]
            ],
            [
                'name' => 'Videographers',
                'slug' => 'videographers',
                'description' => 'Professional video coverage for events',
                'icon' => 'fas fa-video',
                'parent_category' => 'media-production',
                'providers' => [
                    [
                        'name' => 'Chris Martin',
                        'bio' => '4K videography specialist with cinematic style',
                        'base_price' => 40000,
                        'experience_level' => 'Premium',
                        'years_experience' => 9,
                        'languages' => ['English'],
                        'specialties' => ['Weddings', 'Corporate Videos', 'Music Videos'],
                        'equipment_owned' => ['RED Camera', 'Drone', 'Gimbal', 'Professional Audio'],
                        'badge' => '4K Specialist',
                        'rating' => 4.9,
                    ],
                ]
            ],
            
            // Event Staff
            [
                'name' => 'Event Coordinators',
                'slug' => 'event-coordinators',
                'description' => 'Professional event planning and coordination',
                'icon' => 'fas fa-clipboard-list',
                'parent_category' => 'event-staff',
                'providers' => [
                    [
                        'name' => 'Emma Thompson',
                        'bio' => 'Certified event planner with attention to detail',
                        'base_price' => 20000,
                        'price_unit' => 'day',
                        'experience_level' => 'Professional',
                        'years_experience' => 8,
                        'languages' => ['English', 'Sinhala'],
                        'specialties' => ['Wedding Planning', 'Corporate Events', 'Private Parties'],
                        'rating' => 4.8,
                    ],
                ]
            ],
            [
                'name' => 'Event Ushers',
                'slug' => 'event-ushers',
                'description' => 'Professional ushers for guest management',
                'icon' => 'fas fa-user-tie',
                'parent_category' => 'event-staff',
                'providers' => [
                    [
                        'name' => 'Professional Ushering Services',
                        'bio' => 'Team of trained ushers for all events',
                        'base_price' => 5000,
                        'price_unit' => 'person/day',
                        'experience_level' => 'Entry',
                        'years_experience' => 3,
                        'languages' => ['English', 'Sinhala', 'Tamil'],
                        'specialties' => ['Guest Management', 'Crowd Control', 'VIP Services'],
                        'rating' => 4.4,
                    ],
                ]
            ],
        ];
        
        foreach ($categories as $categoryData) {
            $providers = $categoryData['providers'] ?? [];
            unset($categoryData['providers']);
            
            // Create category
            $category = ServiceCategory::create($categoryData);
            
            // Create providers for this category
            foreach ($providers as $providerData) {
                $providerData['service_category_id'] = $category->id;
                $providerData['slug'] = Str::slug($providerData['stage_name'] ?? $providerData['name']);
                $providerData['short_description'] = $providerData['bio'];
                $providerData['features'] = $this->getDefaultFeatures($category->slug);
                $providerData['min_booking_hours'] = $this->getMinBookingHours($category->slug);
                $providerData['equipment_provided'] = $providerData['equipment_provided'] ?? false;
                $providerData['total_reviews'] = rand(10, 50);
                
                $provider = ServiceProvider::create($providerData);
                
                // Create pricing tiers
                $this->createPricingTiers($provider, $category->slug);
                
                // Create sample media
                $this->createSampleMedia($provider, $category->slug);
            }
        }
    }
    
    private function getDefaultFeatures($categorySlug)
    {
        $features = [
            'professional-djs' => [
                'Professional DJ equipment',
                'Music library with latest hits',
                'Light coordination',
                'MC services',
                'Setup and breakdown'
            ],
            'photographers' => [
                'High-resolution images',
                'Post-production editing',
                'Online gallery',
                'Print-ready files',
                'Quick turnaround'
            ],
            'videographers' => [
                '4K video quality',
                'Professional editing',
                'Highlight reel',
                'Full event coverage',
                'Digital delivery'
            ],
        ];
        
        return $features[$categorySlug] ?? ['Professional service', 'Experienced provider', 'Quality guaranteed'];
    }
    
    private function getMinBookingHours($categorySlug)
    {
        $hours = [
            'professional-djs' => 4,
            'event-emcees' => 2,
            'live-bands' => 3,
            'sound-engineers' => 4,
            'lighting-technicians' => 4,
            'photographers' => 4,
            'videographers' => 4,
            'event-coordinators' => 8,
            'event-ushers' => 8,
        ];
        
        return $hours[$categorySlug] ?? 4;
    }
    
    private function createPricingTiers($provider, $categorySlug)
    {
        $pricingStructures = [
            'professional-djs' => [
                ['tier_name' => 'Basic (4 hours)', 'duration' => '4 hours', 'price' => $provider->base_price],
                ['tier_name' => 'Standard (6 hours)', 'duration' => '6 hours', 'price' => $provider->base_price * 1.4, 'is_popular' => true],
                ['tier_name' => 'Premium (8 hours)', 'duration' => '8 hours', 'price' => $provider->base_price * 1.8],
            ],
            'photographers' => [
                ['tier_name' => 'Half Day', 'duration' => '4 hours', 'price' => $provider->base_price],
                ['tier_name' => 'Full Day', 'duration' => '8 hours', 'price' => $provider->base_price * 1.8, 'is_popular' => true],
                ['tier_name' => 'Full Day + Album', 'duration' => '8 hours', 'price' => $provider->base_price * 2.2],
            ],
            'videographers' => [
                ['tier_name' => 'Highlights Only', 'duration' => '4 hours', 'price' => $provider->base_price * 0.7],
                ['tier_name' => 'Full Coverage', 'duration' => '8 hours', 'price' => $provider->base_price, 'is_popular' => true],
                ['tier_name' => 'Cinematic Package', 'duration' => '8 hours', 'price' => $provider->base_price * 1.5],
            ],
        ];
        
        $tiers = $pricingStructures[$categorySlug] ?? [
            ['tier_name' => 'Standard', 'duration' => '4 hours', 'price' => $provider->base_price],
        ];
        
        foreach ($tiers as $index => $tier) {
            ServiceProviderPricing::create([
                'service_provider_id' => $provider->id,
                'tier_name' => $tier['tier_name'],
                'price' => $tier['price'],
                'duration' => $tier['duration'],
                'included_features' => $this->getTierFeatures($categorySlug, $tier['tier_name']),
                'is_popular' => $tier['is_popular'] ?? false,
                'sort_order' => $index,
            ]);
        }
    }
    
    private function getTierFeatures($categorySlug, $tierName)
    {
        $features = [
            'professional-djs' => [
                'Basic (4 hours)' => ['Basic sound system', 'DJ performance', 'Basic lighting'],
                'Standard (6 hours)' => ['Professional sound system', 'DJ performance', 'Light show', 'MC services'],
                'Premium (8 hours)' => ['Premium sound system', 'DJ performance', 'Full light show', 'MC services', 'Special effects'],
            ],
        ];
        
        return $features[$categorySlug][$tierName] ?? ['Professional service', 'Quality guaranteed'];
    }
    
    private function createSampleMedia($provider, $categorySlug)
    {
        $mediaUrls = [
            'professional-djs' => [
                ['type' => 'photo', 'url' => 'https://images.unsplash.com/photo-1571266028243-e4733b0f0bb0?w=800', 'title' => 'DJ Setup'],
                ['type' => 'photo', 'url' => 'https://images.unsplash.com/photo-1493676304819-0d7a8d026dcf?w=800', 'title' => 'Live Performance'],
                ['type' => 'video', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'title' => 'Performance Highlights'],
            ],
            'photographers' => [
                ['type' => 'photo', 'url' => 'https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=800', 'title' => 'Wedding Photography'],
                ['type' => 'photo', 'url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800', 'title' => 'Event Coverage'],
                ['type' => 'photo', 'url' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=800', 'title' => 'Portrait Work'],
            ],
            'videographers' => [
                ['type' => 'photo', 'url' => 'https://images.unsplash.com/photo-1579632652768-6cb9dcf85912?w=800', 'title' => 'Video Production'],
                ['type' => 'video', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'title' => 'Wedding Highlights'],
            ],
        ];
        
        $media = $mediaUrls[$categorySlug] ?? [
            ['type' => 'photo', 'url' => 'https://via.placeholder.com/800x600', 'title' => 'Portfolio Image'],
        ];
        
        foreach ($media as $index => $item) {
            ServiceProviderMedia::create([
                'service_provider_id' => $provider->id,
                'type' => $item['type'],
                'url' => $item['url'],
                'thumbnail_url' => $item['type'] === 'video' ? 'https://via.placeholder.com/400x300' : $item['url'],
                'title' => $item['title'],
                'is_featured' => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }
}