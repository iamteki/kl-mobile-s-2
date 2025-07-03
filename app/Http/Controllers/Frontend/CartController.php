<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderPricing;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;
    
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
    /**
     * Display cart page (now uses Livewire component)
     */
    public function index()
    {
        return view('frontend.cart.index');
    }
    
    /**
     * Add item to cart (AJAX endpoint)
     */
    public function add(Request $request)
    {
        try {
            switch ($request->type) {
                case 'product':
                    return $this->addProduct($request);
                    
                case 'service_provider':
                    return $this->addServiceProvider($request);
                    
                case 'package':
                    return $this->addPackage($request);
                    
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid item type'
                    ], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Cart add error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart: ' . $e->getMessage()
            ], 500);
        }
    }
    
    protected function addProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
            'rental_days' => 'required|integer|min:1',
            'event_date' => 'required|date|after:today',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        $variation = $request->variation_id ? ProductVariation::find($request->variation_id) : null;
        
        $itemData = [
            'type' => 'product',
            'product_id' => $product->id,
            'variation_id' => $variation ? $variation->id : null,
            'name' => $product->name . ($variation ? ' - ' . $variation->name : ''),
            'price' => $variation ? $variation->price : $product->base_price,
            'quantity' => $request->quantity,
            'rental_days' => $request->rental_days,
            'event_date' => $request->event_date,
            'image' => $product->main_image_url ?? $product->image ?? 'https://via.placeholder.com/300',
            'category' => $product->category->name ?? 'Equipment'
        ];
        
        $this->cartService->addItem($itemData);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $this->cartService->getItemCount()
        ]);
    }
    
    protected function addServiceProvider(Request $request)
    {
        $rules = [
            'provider_id' => 'required|exists:service_providers,id',
            'pricing_tier_id' => 'nullable|exists:service_provider_pricing,id',
            'event_date' => 'required|date|after:today',
            'start_time' => 'required',
        ];
        
        if (!$request->pricing_tier_id) {
            $rules['duration'] = 'required|integer|min:1';
        }
        
        $request->validate($rules);
        
        $provider = ServiceProvider::findOrFail($request->provider_id);
        
        if ($request->pricing_tier_id) {
            $pricingTier = ServiceProviderPricing::findOrFail($request->pricing_tier_id);
            $price = $pricingTier->price;
            preg_match('/(\d+)/', $pricingTier->duration, $matches);
            $duration = isset($matches[1]) ? (int)$matches[1] : $provider->min_booking_hours;
            $tierName = $pricingTier->tier_name;
        } else {
            $duration = (int)$request->duration;
            $price = $provider->base_price * ($duration / $provider->min_booking_hours);
            $tierName = 'Standard';
        }
        
        $itemData = [
            'type' => 'service_provider',
            'provider_id' => $provider->id,
            'pricing_tier_id' => $request->pricing_tier_id,
            'name' => $provider->display_name . ' - ' . $tierName,
            'price' => $price,
            'quantity' => 1,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'duration' => $duration,
            'duration_text' => $duration . ' hours',
            'image' => $provider->profile_image_url ?? 'https://via.placeholder.com/300',
            'category' => $provider->category->name ?? 'Service',
        ];
        
        $this->cartService->addItem($itemData);
        
        return response()->json([
            'success' => true,
            'message' => 'Service provider added to cart',
            'cart_count' => $this->cartService->getItemCount()
        ]);
    }
    
    protected function addPackage(Request $request)
    {
        // Implement package logic
        return response()->json([
            'success' => false,
            'message' => 'Package functionality coming soon'
        ]);
    }
}