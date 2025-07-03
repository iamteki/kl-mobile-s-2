<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    /**
     * Display all service categories
     */
    public function index(Request $request)
    {
        // Get parent category filter
        $parentCategory = $request->get('parent');
        
        // Get all active categories
        $query = ServiceCategory::active()
            ->withCount('activeProviders')
            ->orderBy('sort_order')
            ->orderBy('name');
            
        if ($parentCategory) {
            $query->where('parent_category', $parentCategory);
        }
        
        $categories = $query->get();
        
        // Group by parent category for display
        $groupedCategories = $categories->groupBy('parent_category');
        
        // Get parent categories for tabs
        $parentCategories = [
            'entertainment' => 'Entertainment',
            'technical-crew' => 'Technical Crew',
            'media-production' => 'Media Production',
            'event-staff' => 'Event Staff'
        ];
        
        return view('frontend.services.index', compact(
            'categories',
            'groupedCategories',
            'parentCategories',
            'parentCategory'
        ));
    }
    
    /**
     * Display service providers in a category
     */
    public function show(Request $request, $categorySlug)
    {
        // Get the category
        $category = ServiceCategory::where('slug', $categorySlug)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Build query for providers
        $query = ServiceProvider::with(['category', 'pricingTiers', 'media'])
            ->where('service_category_id', $category->id)
            ->where('status', 'active');
        
        // Apply filters
        if ($request->has('experience_level')) {
            $query->where('experience_level', $request->get('experience_level'));
        }
        
        if ($request->has('price_range')) {
            $priceRange = $request->get('price_range');
            switch ($priceRange) {
                case 'budget':
                    $query->where('base_price', '<=', 20000);
                    break;
                case 'mid':
                    $query->whereBetween('base_price', [20000, 50000]);
                    break;
                case 'premium':
                    $query->where('base_price', '>=', 50000);
                    break;
            }
        }
        
        if ($request->has('rating')) {
            $query->where('rating', '>=', $request->get('rating'));
        }
        
        if ($request->has('language')) {
            $query->whereJsonContains('languages', $request->get('language'));
        }
        
        // Apply sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('total_bookings', 'desc');
                break;
            default:
                $query->orderBy('featured', 'desc')
                    ->orderBy('sort_order', 'asc');
        }
        
        // Paginate results
        $providers = $query->paginate(12);
        
        // Get filters data
        $languages = ServiceProvider::where('service_category_id', $category->id)
            ->whereNotNull('languages')
            ->pluck('languages')
            ->flatten()
            ->unique()
            ->sort()
            ->values();
        
        return view('frontend.services.category', compact(
            'category',
            'providers',
            'languages'
        ));
    }
}