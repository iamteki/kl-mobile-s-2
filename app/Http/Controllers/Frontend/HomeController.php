<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get categories for homepage display
        $categories = collect();
        $featuredProducts = collect();
        $packages = collect();
        
        try {
          $categories = Category::where('status', 'active')
    ->where('show_on_homepage', true)
    ->withCount(['products' => function ($query) {
        $query->where('status', 'active');
    }])
    ->orderBy('sort_order')
    ->limit(8)
    ->get()
    ->map(function ($category) {
        // Add icon mapping
        $iconMap = [
            'sound-equipment' => 'fas fa-volume-up',
            'lighting' => 'fas fa-lightbulb',
            'led-screens' => 'fas fa-tv',
            'dj-equipment' => 'fas fa-headphones',
            'tables-chairs' => 'fas fa-chair',
            'tents-canopy' => 'fas fa-campground',
            'photo-booths' => 'fas fa-camera',
            'power-distribution' => 'fas fa-bolt',
        ];
        
        $category->icon = $iconMap[$category->slug] ?? 'fas fa-box';
        return $category;
    });
            
            // Get featured products
            $featuredProducts = Product::with(['category', 'media'])
                ->where('status', 'active')
                ->where('featured', true)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get()
                ->map(function ($product) {
                    // Add computed properties
                    $product->main_image_url = $product->getFirstMediaUrl('main');
                    $product->availability_class = $this->getAvailabilityClass($product->available_quantity);
                    $product->specifications = $this->getProductSpecifications($product);
                    return $product;
                });
            
           // Get packages
$packages = Package::where('status', 'active')
    ->orderBy('sort_order')
    ->limit(3)
    ->get()
    ->map(function ($package, $index) {
        // Add badge for middle package
        if ($index === 1) {
            $package->badge = 'Most Popular';
        }
        
        // Features are already cast to array in the model
        // No need to decode - just ensure it's an array
        if (!is_array($package->features)) {
            $package->features = [];
        }
        
        return $package;
    });
                
        } catch (\Exception $e) {
            // Log the error
            \Log::error('HomeController index error: ' . $e->getMessage());
            
            // Continue with empty collections
        }
        
        return view('frontend.home.index', compact('categories', 'featuredProducts', 'packages'));
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
        // This would pull from product attributes based on category
        // For now, return sample specs
        $specs = [];
        
        if ($product->category->slug === 'sound-equipment') {
            $specs[] = ['icon' => 'fas fa-bolt', 'value' => '1000W RMS Power'];
            $specs[] = ['icon' => 'fas fa-users', 'value' => 'Suitable for 200 pax'];
            $specs[] = ['icon' => 'fas fa-weight', 'value' => '45kg per unit'];
        } elseif ($product->category->slug === 'lighting') {
            $specs[] = ['icon' => 'fas fa-lightbulb', 'value' => 'LED Technology'];
            $specs[] = ['icon' => 'fas fa-palette', 'value' => 'RGB Color Mixing'];
            $specs[] = ['icon' => 'fas fa-wifi', 'value' => 'DMX Control'];
        }
        
        return $specs;
    }
}