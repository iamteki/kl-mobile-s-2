<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $sessionKey = 'cart';
    
    /**
     * Get the current cart
     */
    public function getCart()
    {
        $cart = Session::get($this->sessionKey, [
            'items' => [],
            'total' => 0,
            'count' => 0
        ]);
        
        // Recalculate totals to ensure accuracy
        $this->recalculateTotals($cart);
        
        return $cart;
    }
    
    /**
     * Add item to cart
     */
    public function addItem($productId, $quantity = 1, $variationId = null, $eventDate = null)
    {
        $cart = $this->getCart();
        
        // Get product details
        $product = Product::findOrFail($productId);
        $variation = $variationId ? ProductVariation::findOrFail($variationId) : null;
        
        // Create unique cart item key
        $cartKey = $this->generateCartKey($productId, $variationId);
        
        // Check if item already exists in cart
        if (isset($cart['items'][$cartKey])) {
            $cart['items'][$cartKey]['quantity'] += $quantity;
        } else {
            $cart['items'][$cartKey] = [
                'id' => $cartKey,
                'product_id' => $productId,
                'variation_id' => $variationId,
                'name' => $product->name,
                'variation' => $variation ? $variation->name : null,
                'price' => $variation ? $variation->price : $product->base_price,
                'quantity' => $quantity,
                'image' => $product->getFirstMediaUrl('main'),
                'category' => $product->category->name,
                'event_date' => $eventDate
            ];
        }
        
        // Recalculate totals
        $this->recalculateTotals($cart);
        
        // Save to session
        Session::put($this->sessionKey, $cart);
        
        return $cart;
    }
    
    /**
     * Update item quantity
     */
    public function updateQuantity($itemId, $quantity)
    {
        $cart = $this->getCart();
        
        if (isset($cart['items'][$itemId])) {
            if ($quantity <= 0) {
                unset($cart['items'][$itemId]);
            } else {
                $cart['items'][$itemId]['quantity'] = $quantity;
            }
            
            $this->recalculateTotals($cart);
            Session::put($this->sessionKey, $cart);
        }
        
        return $cart;
    }
    
    /**
     * Remove item from cart
     */
    public function removeItem($itemId)
    {
        $cart = $this->getCart();
        
        if (isset($cart['items'][$itemId])) {
            unset($cart['items'][$itemId]);
            $this->recalculateTotals($cart);
            Session::put($this->sessionKey, $cart);
        }
        
        return $cart;
    }
    
    /**
     * Clear the cart
     */
    public function clearCart()
    {
        Session::forget($this->sessionKey);
        
        return [
            'items' => [],
            'total' => 0,
            'count' => 0
        ];
    }
    
    /**
     * Get cart count
     */
    public function getCount()
    {
        $cart = $this->getCart();
        return $cart['count'];
    }
    
    /**
     * Get cart total
     */
    public function getTotal()
    {
        $cart = $this->getCart();
        return $cart['total'];
    }
    
    /**
     * Check if cart has items
     */
    public function hasItems()
    {
        $cart = $this->getCart();
        return count($cart['items']) > 0;
    }
    
    /**
     * Get a specific cart item
     */
    public function getItem($itemId)
    {
        $cart = $this->getCart();
        return $cart['items'][$itemId] ?? null;
    }
    
    /**
     * Update event details for all cart items
     */
    public function updateEventDetails($eventDate, $eventType = null, $venue = null)
    {
        $cart = $this->getCart();
        
        foreach ($cart['items'] as &$item) {
            $item['event_date'] = $eventDate;
            if ($eventType) {
                $item['event_type'] = $eventType;
            }
            if ($venue) {
                $item['venue'] = $venue;
            }
        }
        
        Session::put($this->sessionKey, $cart);
        
        return $cart;
    }
    
    /**
     * Validate cart items availability
     */
    public function validateAvailability($eventDate)
    {
        $cart = $this->getCart();
        $unavailableItems = [];
        
        foreach ($cart['items'] as $itemId => $item) {
            // Check product availability
            $product = Product::find($item['product_id']);
            
            if (!$product || $product->status !== 'active') {
                $unavailableItems[] = $item['name'];
                continue;
            }
            
            // Check quantity availability
            if ($item['variation_id']) {
                $variation = ProductVariation::find($item['variation_id']);
                if (!$variation || $variation->available_quantity < $item['quantity']) {
                    $unavailableItems[] = $item['name'] . ' (' . $item['variation'] . ')';
                }
            } else {
                if ($product->available_quantity < $item['quantity']) {
                    $unavailableItems[] = $item['name'];
                }
            }
            
            // TODO: Check booking availability for the event date
        }
        
        return [
            'valid' => empty($unavailableItems),
            'unavailable_items' => $unavailableItems
        ];
    }
    
    /**
     * Generate unique cart key
     */
    private function generateCartKey($productId, $variationId = null)
    {
        return $variationId ? "{$productId}_{$variationId}" : $productId;
    }
    
    /**
     * Recalculate cart totals
     */
    private function recalculateTotals(&$cart)
    {
        $total = 0;
        $count = 0;
        
        foreach ($cart['items'] as $item) {
            $total += $item['price'] * $item['quantity'];
            $count += $item['quantity'];
        }
        
        $cart['total'] = $total;
        $cart['count'] = $count;
    }
    
    /**
     * Apply coupon to cart
     */
    public function applyCoupon($couponCode)
    {
        $cart = $this->getCart();
        
        // TODO: Implement coupon validation and discount calculation
        // For now, just store the coupon code
        $cart['coupon'] = $couponCode;
        $cart['discount'] = 0;
        
        Session::put($this->sessionKey, $cart);
        
        return $cart;
    }
    
    /**
     * Remove coupon from cart
     */
    public function removeCoupon()
    {
        $cart = $this->getCart();
        
        unset($cart['coupon']);
        unset($cart['discount']);
        
        Session::put($this->sessionKey, $cart);
        
        return $cart;
    }
}