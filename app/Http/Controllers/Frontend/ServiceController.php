<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // Get services grouped by category
        $services = $this->getServices($request);
        
        return view('frontend.services.index', compact('services'));
    }
    
    public function show($serviceSlug)
    {
        $service = Service::where('slug', $serviceSlug)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Get related services
        $relatedServices = Service::where('category', $service->category)
            ->where('id', '!=', $service->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();
        
        return view('frontend.services.show', compact('service', 'relatedServices'));
    }
    
    private function getServices($request)
    {
        // Mock data for services
        $services = collect([
            // Entertainment
            [
                'id' => 1,
                'name' => 'Professional DJs',
                'slug' => 'professional-djs',
                'category' => 'Entertainment',
                'image_url' => 'https://images.unsplash.com/photo-1571266028243-e4733b0f0bb0?w=600&h=400&fit=crop',
                'badge' => 'Most Popular',
                'badge_class' => 'badge-popular',
                'features' => [
                    'Experienced club & event DJs',
                    'All music genres available',
                    'Professional DJ equipment included',
                    '4-8 hours performance'
                ],
                'starting_price' => 25000,
                'price_unit' => 'event'
            ],
            [
                'id' => 2,
                'name' => 'Professional Emcees',
                'slug' => 'professional-emcees',
                'category' => 'Entertainment',
                'image_url' => 'https://images.unsplash.com/photo-1560439514-4e9645039924?w=600&h=400&fit=crop',
                'badge' => 'High Demand',
                'features' => [
                    'Bilingual emcees available',
                    'Corporate & wedding specialists',
                    'Professional attire included',
                    'Script coordination service'
                ],
                'starting_price' => 20000,
                'price_unit' => 'event'
            ],
            [
                'id' => 3,
                'name' => 'Live Bands',
                'slug' => 'live-bands',
                'category' => 'Entertainment',
                'image_url' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&h=400&fit=crop',
                'badge' => 'Premium',
                'badge_class' => 'badge-premium',
                'features' => [
                    '4-8 piece band configurations',
                    'Jazz, pop, rock, classical',
                    'Professional sound included',
                    '2-4 hours performance sets'
                ],
                'starting_price' => 60000,
                'price_unit' => 'event'
            ],
            
            // Technical Crew
            [
                'id' => 4,
                'name' => 'Sound Engineers',
                'slug' => 'sound-engineers',
                'category' => 'Technical Crew',
                'image_url' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&h=400&fit=crop',
                'features' => [
                    'Professional audio mixing',
                    'Equipment setup & operation',
                    'Live sound management',
                    'Technical troubleshooting'
                ],
                'starting_price' => 15000,
                'price_unit' => 'day'
            ],
            [
                'id' => 5,
                'name' => 'Lighting Engineers',
                'slug' => 'lighting-engineers',
                'category' => 'Technical Crew',
                'image_url' => 'https://images.unsplash.com/photo-1565035010268-a3816f98589a?w=600&h=400&fit=crop',
                'features' => [
                    'Professional lighting design',
                    'DMX programming & operation',
                    'Effect lighting coordination',
                    'Full event coverage'
                ],
                'starting_price' => 18000,
                'price_unit' => 'day'
            ],
            
            // Media Production
            [
                'id' => 6,
                'name' => 'Videographers',
                'slug' => 'videographers',
                'category' => 'Media Production',
                'image_url' => 'https://images.unsplash.com/photo-1606986628253-05620e9b0a80?w=600&h=400&fit=crop',
                'badge' => '4K Video',
                'features' => [
                    '4K professional videography',
                    'Multi-camera coverage',
                    'Drone footage available',
                    'Same-day highlights option'
                ],
                'starting_price' => 40000,
                'price_unit' => 'event'
            ],
            [
                'id' => 7,
                'name' => 'Photographers',
                'slug' => 'photographers',
                'category' => 'Media Production',
                'image_url' => 'https://images.unsplash.com/photo-1554048612-b6a482bc67e5?w=600&h=400&fit=crop',
                'features' => [
                    'Professional event photography',
                    'Candid & portrait shots',
                    'Instant photo sharing',
                    'Post-production editing'
                ],
                'starting_price' => 30000,
                'price_unit' => 'event'
            ],
            
            // Event Staff
            [
                'id' => 8,
                'name' => 'Event Runners',
                'slug' => 'event-runners',
                'category' => 'Event Staff',
                'image_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&h=400&fit=crop',
                'features' => [
                    'Event coordination support',
                    'Guest management',
                    'Logistics assistance',
                    'Professional & reliable'
                ],
                'starting_price' => 8000,
                'price_unit' => 'day'
            ],
            [
                'id' => 9,
                'name' => 'Event Usherettes',
                'slug' => 'event-usherettes',
                'category' => 'Event Staff',
                'image_url' => 'https://images.unsplash.com/photo-1529636798458-92182e662485?w=600&h=400&fit=crop',
                'features' => [
                    'Professional female staff',
                    'Guest reception & guidance',
                    'Registration assistance',
                    'Uniform provided'
                ],
                'starting_price' => 6000,
                'price_unit' => 'day'
            ],
            [
                'id' => 10,
                'name' => 'Event Ushers',
                'slug' => 'event-ushers',
                'category' => 'Event Staff',
                'image_url' => 'https://images.unsplash.com/photo-1549923746-c502d488b3ea?w=600&h=400&fit=crop',
                'features' => [
                    'Professional male staff',
                    'Crowd management',
                    'VIP assistance',
                    'Security coordination'
                ],
                'starting_price' => 6000,
                'price_unit' => 'day'
            ],
            
            // Special Performances
            [
                'id' => 11,
                'name' => 'Special Performances',
                'slug' => 'special-performances',
                'category' => 'Entertainment',
                'image_url' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=600&h=400&fit=crop',
                'badge' => 'Exclusive',
                'badge_class' => 'badge-premium',
                'features' => [
                    'Cultural dance performances',
                    'Magic shows & illusions',
                    'Fire shows & acrobatics',
                    'Celebrity appearances'
                ],
                'starting_price' => 35000,
                'price_unit' => 'show'
            ]
        ]);
        
        // Filter by category if requested
        $category = $request->get('category');
        if ($category && $category !== 'all') {
            $services = $services->filter(function ($service) use ($category) {
                return strtolower(str_replace(' ', '-', $service['category'])) === $category;
            });
        }
        
        // Filter by experience level
        $experience = $request->get('experience');
        if ($experience) {
            // Apply experience filter logic here
        }
        
        // Filter by event type
        $eventType = $request->get('event_type');
        if ($eventType) {
            // Apply event type filter logic here
        }
        
        return $services->map(function ($service) {
            return (object) $service;
        });
    }
}