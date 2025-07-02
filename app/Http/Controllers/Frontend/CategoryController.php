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
            ->get();
            
        return view('frontend.categories.index', compact('categories'));
    }
    
    public function show(Request $request, $slug)
    {
        // Find category by slug
        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Load category with products count
        $category->loadCount(['products' => function ($query) {
            $query->where('status', 'active');
        }]);
        
        // Get all categories for sidebar
        $allCategories = Category::where('status', 'active')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('sort_order')
            ->get();
        
        // Build products query
        $query = $category->products()
            ->where('status', 'active')
            ->with(['category']);
        
        // Apply filters
        $query = $this->applyFilters($query, $request);
        
        // Apply sorting
        $query = $this->applySorting($query, $request);
        
        // Paginate results
        $products = $query->paginate(12)->withQueryString();
        
        // Get filter options
        $filters = $this->getFilterOptions($category);
        
        return view('frontend.categories.show', compact(
            'category',
            'products',
            'filters',
            'allCategories'
        ));
    }
    
    private function applyFilters($query, Request $request)
    {
        // Subcategory filter
        if ($request->filled('subcategory')) {
            $subcategories = array_filter((array) $request->subcategory);
            if (!empty($subcategories)) {
                $query->whereIn('subcategory', $subcategories);
            }
        }
        
        // Brand filter
        if ($request->filled('brand')) {
            $brands = array_filter((array) $request->brand);
            if (!empty($brands)) {
                $query->whereIn('brand', $brands);
            }
        }
        
        // Power output filter (for specific categories)
        if ($request->filled('power_output')) {
            $powerOutputs = array_filter((array) $request->power_output);
            if (!empty($powerOutputs)) {
                $query->whereIn('specifications->power_output', $powerOutputs);
            }
        }
        
        // Price range filter
        if ($request->filled('min_price') && is_numeric($request->min_price)) {
            $query->where('base_price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('base_price', '<=', $request->max_price);
        }
        
        // Availability filter
        if ($request->available_only === 'true') {
            $query->where('available_quantity', '>', 0);
        }
        
        return $query;
    }
    
    private function applySorting($query, Request $request)
    {
        switch ($request->sort) {
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
                $query->orderBy('featured', 'desc')
                      ->orderBy('sort_order', 'asc');
        }
        
        return $query;
    }
    
    private function getFilterOptions(Category $category)
    {
        $products = $category->products()
            ->where('status', 'active')
            ->get();
        
        $filters = [
            'subcategories' => [],
            'brands' => [],
            'powerOutputs' => []
        ];
        
        // Get unique subcategories
        $subcategories = $products->pluck('subcategory')
            ->filter()
            ->unique()
            ->sort();
            
        foreach ($subcategories as $subcategory) {
            $filters['subcategories'][] = [
                'name' => $subcategory,
                'count' => $products->where('subcategory', $subcategory)->count()
            ];
        }
        
        // Get unique brands
        $brands = $products->pluck('brand')
            ->filter()
            ->unique()
            ->sort();
            
        foreach ($brands as $brand) {
            $filters['brands'][] = [
                'name' => $brand,
                'count' => $products->where('brand', $brand)->count()
            ];
        }
        
        // Get power outputs for specific categories
        if (in_array($category->slug, ['sound-systems', 'speakers', 'pa-systems'])) {
            $powerOutputs = $products->pluck('specifications.power_output')
                ->filter()
                ->unique()
                ->sort()
                ->values();
                
            foreach ($powerOutputs as $power) {
                $filters['powerOutputs'][] = [
                    'value' => $power,
                    'range' => $this->formatPowerRange($power),
                    'count' => $products->filter(function ($product) use ($power) {
                        return data_get($product, 'specifications.power_output') == $power;
                    })->count()
                ];
            }
        }
        
        return $filters;
    }
    
    private function formatPowerRange($power)
    {
        $value = intval($power);
        if ($value < 500) return 'Under 500W';
        if ($value < 1000) return '500W - 1000W';
        if ($value < 2000) return '1000W - 2000W';
        return 'Over 2000W';
    }
}