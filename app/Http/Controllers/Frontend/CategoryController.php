<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                // Add icon mapping
                $iconMap = [
                    'sound-equipment' => 'fas fa-volume-up',
                    'lighting' => 'fas fa-lightbulb',
                    'led-screens' => 'fas fa-tv',
                    'dj-equipment' => 'fas fa-headphones',
                    'backdrops' => 'fas fa-image',
                    'tables-chairs' => 'fas fa-chair',
                    'tents-canopy' => 'fas fa-campground',
                    'photo-booths' => 'fas fa-camera',
                    'power-distribution' => 'fas fa-bolt',
                    'dance-floors' => 'fas fa-compact-disc',
                    'trusses' => 'fas fa-project-diagram',
                    'led-tvs' => 'fas fa-desktop',
                    'event-props' => 'fas fa-magic',
                    'decoration-items' => 'fas fa-star',
                    'band-equipment' => 'fas fa-guitar',
                    'launching-gimmicks' => 'fas fa-rocket',
                ];
                
                $category->icon = $iconMap[$category->slug] ?? 'fas fa-box';
                return $category;
            });
        
        return view('frontend.categories.index', compact('categories'));
    }
    
    public function show(Request $request, $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Get all categories for sidebar
        $allCategories = Category::where('status', 'active')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->get();
        
        // Build product query
        $query = Product::with(['category', 'media', 'variations'])
            ->where('category_id', $category->id)
            ->where('status', 'active');
        
        // Apply filters
        $this->applyFilters($query, $request);
        
        // Apply sorting
        $sortBy = $request->get('sort', 'featured');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('featured', 'desc')->orderBy('sort_order', 'asc');
        }
        
        // Paginate results
        $products = $query->paginate(12)->withQueryString();
        
        // Transform products
        $products->getCollection()->transform(function ($product) {
            $product->main_image_url = $product->getFirstMediaUrl('main');
            $product->availability_class = $this->getAvailabilityClass($product->available_quantity);
            $product->specifications = $this->getProductSpecifications($product);
            $product->badges = $this->getProductBadges($product);
            return $product;
        });
        
        // Get filters data
        $filters = $this->getFiltersForCategory($category);
        
        return view('frontend.categories.show', compact(
            'category',
            'allCategories',
            'products',
            'filters'
        ));
    }
    
    private function applyFilters($query, Request $request)
    {
        // Subcategory filter
        if ($request->has('subcategory')) {
            $query->whereIn('subcategory', (array) $request->get('subcategory'));
        }
        
        // Brand filter
        if ($request->has('brand')) {
            $query->whereIn('brand', (array) $request->get('brand'));
        }
        
        // Price range filter
        if ($request->has('min_price')) {
            $query->where('base_price', '>=', $request->get('min_price'));
        }
        
        if ($request->has('max_price')) {
            $query->where('base_price', '<=', $request->get('max_price'));
        }
        
        // Power output filter (for applicable categories)
        if ($request->has('power_output')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('attribute_key', 'power_output')
                  ->whereIn('attribute_value', (array) $request->get('power_output'));
            });
        }
        
        // Availability filter
        if ($request->get('available_only') === 'true') {
            $query->where('available_quantity', '>', 0);
        }
    }
    
    private function getFiltersForCategory($category)
    {
        // Get unique subcategories
        $subcategories = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->whereNotNull('subcategory')
            ->distinct()
            ->pluck('subcategory')
            ->map(function ($subcategory) use ($category) {
                $count = Product::where('category_id', $category->id)
                    ->where('subcategory', $subcategory)
                    ->where('status', 'active')
                    ->count();
                
                return [
                    'name' => $subcategory,
                    'count' => $count
                ];
            });
        
        // Get unique brands
        $brands = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->whereNotNull('brand')
            ->distinct()
            ->pluck('brand')
            ->map(function ($brand) use ($category) {
                $count = Product::where('category_id', $category->id)
                    ->where('brand', $brand)
                    ->where('status', 'active')
                    ->count();
                
                return [
                    'name' => $brand,
                    'count' => $count
                ];
            });
        
        // Get power output options for sound equipment
        $powerOutputs = [];
        if ($category->slug === 'sound-equipment') {
            $powerOutputs = [
                ['range' => 'Up to 500W', 'value' => '0-500', 'count' => 5],
                ['range' => '500W - 1000W', 'value' => '500-1000', 'count' => 8],
                ['range' => '1000W - 2000W', 'value' => '1000-2000', 'count' => 6],
                ['range' => '2000W+', 'value' => '2000+', 'count' => 5],
            ];
        }
        
        return [
            'subcategories' => $subcategories,
            'brands' => $brands,
            'powerOutputs' => $powerOutputs,
        ];
    }
    
    private function getAvailabilityClass($quantity)
    {
        if ($quantity <= 0) {
            return 'out-stock';
        } elseif ($quantity <= 3) {
            return 'low-stock';
        }
        
        return 'in-stock';
    }
    
    private function getProductSpecifications($product)
    {
        $specs = [];
        
        // Get specifications based on category
        if ($product->category->slug === 'sound-equipment') {
            $specs[] = ['icon' => 'fas fa-bolt', 'value' => '1000W RMS Power'];
            $specs[] = ['icon' => 'fas fa-users', 'value' => 'Suitable for 200 pax'];
            $specs[] = ['icon' => 'fas fa-weight', 'value' => '45kg per unit'];
        }
        
        return $specs;
    }
    
    private function getProductBadges($product)
    {
        $badges = [];
        
        if ($product->featured) {
            $badges[] = 'Popular';
        }
        
        if ($product->created_at->isAfter(now()->subDays(30))) {
            $badges[] = 'New';
        }
        
        return $badges;
    }
}