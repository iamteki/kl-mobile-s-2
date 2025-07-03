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
     * Display cart page
     */
    public function index()
    {
        $cartItems = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();
        $tax = $this->cartService->getTax();
        $total = $this->cartService->getTotal();
        
        return view('frontend.cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }
    
    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        try {
            // Handle different item types
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
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Add product to cart
     */
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
        
        // Prepare cart item data
        $itemData = [
            'type' => 'product',
            'product_id' => $product->id,
            'variation_id' => $variation ? $variation->id : null,
            'name' => $product->name . ($variation ? ' - ' . $variation->name : ''),
            'price' => $variation ? $variation->price : $product->base_price,
            'quantity' => $request->quantity,
            'rental_days' => $request->rental_days,
            'event_date' => $request->event_date,
            'image' => $product->main_image_url ?? $product->image,
        ];
        
        $this->cartService->addItem($itemData);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $this->cartService->getItemCount()
        ]);
    }
    
    /**
     * Add service provider to cart
     */
    protected function addServiceProvider(Request $request)
    {
        $request->validate([
            'provider_id' => 'required|exists:service_providers,id',
            'pricing_tier_id' => 'nullable|exists:service_provider_pricing,id',
            'event_date' => 'required|date|after:today',
            'start_time' => 'required',
            'duration' => 'required_without:pricing_tier_id|integer|min:1',
        ]);
        
        $provider = ServiceProvider::findOrFail($request->provider_id);
        
        // Get pricing details
        if ($request->pricing_tier_id) {
            $pricingTier = ServiceProviderPricing::findOrFail($request->pricing_tier_id);
            $price = $pricingTier->price;
            $duration = $pricingTier->duration;
            $tierName = $pricingTier->tier_name;
        } else {
            $price = $provider->base_price * ($request->duration / $provider->min_booking_hours);
            $duration = $request->duration . ' hours';
            $tierName = 'Standard';
        }
        
        // Prepare cart item data
        $itemData = [
            'type' => 'service_provider',
            'provider_id' => $provider->id,
            'pricing_tier_id' => $request->pricing_tier_id,
            'name' => $provider->display_name . ' - ' . $tierName,
            'price' => $price,
            'quantity' => 1, // Service providers are always quantity 1
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'duration' => $duration,
            'image' => $provider->profile_image_url,
            'category' => $provider->category->name,
        ];
        
        $this->cartService->addItem($itemData);
        
        return response()->json([
            'success' => true,
            'message' => 'Service provider added to cart',
            'cart_count' => $this->cartService->getItemCount()
        ]);
    }
    
    /**
     * Add package to cart
     */
    protected function addPackage(Request $request)
    {
        // Similar implementation for packages
        // ...
    }
    
    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);
        
        if ($request->quantity == 0) {
            $this->cartService->removeItem($id);
            $message = 'Item removed from cart';
        } else {
            $this->cartService->updateQuantity($id, $request->quantity);
            $message = 'Cart updated';
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'subtotal' => $this->cartService->getSubtotal(),
                'tax' => $this->cartService->getTax(),
                'total' => $this->cartService->getTotal(),
                'item_count' => $this->cartService->getItemCount()
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', $message);
    }
    
    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $this->cartService->removeItem($id);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'subtotal' => $this->cartService->getSubtotal(),
                'tax' => $this->cartService->getTax(),
                'total' => $this->cartService->getTotal(),
                'item_count' => $this->cartService->getItemCount()
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }
    
    /**
     * Clear cart
     */
    public function clear()
    {
        $this->cartService->clearCart();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared'
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared');
    }
}