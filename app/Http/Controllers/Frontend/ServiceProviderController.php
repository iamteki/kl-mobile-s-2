<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderReview;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    protected $availabilityService;
    
    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }
    
    /**
     * Display service provider detail
     */
    public function show($categorySlug, $providerSlug)
    {
        // Get the provider with relationships
        $provider = ServiceProvider::with([
            'category',
            'pricingTiers',
            'mediaItems',
            'approvedReviews.customer.user'
        ])
            ->where('slug', $providerSlug)
            ->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            })
            ->where('status', 'active')
            ->firstOrFail();
        
        // Get related providers
        $relatedProviders = ServiceProvider::where('service_category_id', $provider->service_category_id)
            ->where('id', '!=', $provider->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();
        
        // Get featured reviews
        $featuredReviews = $provider->approvedReviews()
            ->where('is_featured', true)
            ->latest()
            ->limit(3)
            ->get();
        
        // Get availability for next 30 days (simplified)
        $availabilityDates = $this->getAvailabilityCalendar($provider);
        
        return view('frontend.services.provider-detail', compact(
            'provider',
            'relatedProviders',
            'featuredReviews',
            'availabilityDates'
        ));
    }
    
    /**
     * Check availability for a specific date
     */
    public function checkAvailability(Request $request, ServiceProvider $provider)
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'duration' => 'required|integer|min:' . $provider->min_booking_hours
        ]);
        
        $date = Carbon::parse($request->date);
        $duration = $request->duration;
        
        // Check if provider is available
        $isAvailable = $provider->isAvailableOn($date);
        
        // Get pricing for the duration
        $pricing = $this->calculatePricing($provider, $duration);
        
        return response()->json([
            'available' => $isAvailable,
            'pricing' => $pricing,
            'message' => $isAvailable 
                ? 'Available for booking!' 
                : 'Not available on this date'
        ]);
    }
    
    /**
     * Get availability calendar
     */
    private function getAvailabilityCalendar($provider)
    {
        $dates = [];
        $start = Carbon::now();
        $end = Carbon::now()->addDays(30);
        
        while ($start <= $end) {
            $dates[$start->format('Y-m-d')] = [
                'available' => $provider->isAvailableOn($start),
                'date' => $start->format('Y-m-d'),
                'display' => $start->format('j')
            ];
            $start->addDay();
        }
        
        return $dates;
    }
    
    /**
     * Calculate pricing based on duration
     */
    private function calculatePricing($provider, $duration)
    {
        // Find appropriate pricing tier
        $pricingTier = $provider->pricingTiers()
            ->where('duration', '<=', $duration)
            ->orderBy('duration', 'desc')
            ->first();
        
        if (!$pricingTier) {
            // Use base price calculation
            $price = $provider->base_price;
            
            if ($provider->price_unit === 'hour') {
                $price = $provider->base_price * $duration;
            }
        } else {
            $price = $pricingTier->price;
        }
        
        return [
            'base_price' => $price,
            'duration' => $duration,
            'tier' => $pricingTier ? $pricingTier->tier_name : 'Standard',
            'total' => $price
        ];
    }
}