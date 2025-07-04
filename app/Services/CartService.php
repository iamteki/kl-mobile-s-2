<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use App\Models\Coupon;

class CartService
{
    protected string $sessionKey = 'cart';
    
    /**
     * Get all cart items
     */
    public function getItems(): array
    {
        return Session::get($this->sessionKey, []);
    }
    
    /**
     * Check if cart has items
     */
    public function hasItems(): bool
    {
        return count($this->getItems()) > 0;
    }
    
    /**
     * Get cart summary with all items
     */
    public function getCart(): array
    {
        $items = $this->getItems();
        $subtotal = $this->getSubtotal();
        $discount = Session::get('cart_discount', 0);
        $tax = $this->getTax($subtotal - $discount);
        
        return [
            'items' => $items,
            'count' => $this->getItemCount(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $subtotal - $discount + $tax,
            'coupon' => Session::get('cart_coupon', null)
        ];
    }
    
    /**
     * Add item to cart
     */
    public function addItem(array $itemData): string
    {
        $cart = $this->getItems();
        $cartItemId = $this->generateCartItemId($itemData);
        
        // For products, check if same item exists and merge quantities
        if ($itemData['type'] === 'product' && isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $itemData['quantity'];
            $cart[$cartItemId]['updated_at'] = now();
        } else {
            $itemData['id'] = $cartItemId;
            $itemData['added_at'] = now();
            $cart[$cartItemId] = $itemData;
        }
        
        Session::put($this->sessionKey, $cart);
        Session::save();
        
        return $cartItemId;
    }
    
    /**
     * Generate unique cart item ID
     */
    protected function generateCartItemId(array $itemData): string
    {
        switch ($itemData['type']) {
            case 'product':
                return 'product_' . $itemData['product_id'] . '_' . ($itemData['variation_id'] ?? 0) . '_' . uniqid();
                
            case 'service_provider':
                return 'service_' . $itemData['provider_id'] . '_' . ($itemData['pricing_tier_id'] ?? 0) . '_' . uniqid();
                
            case 'package':
                return 'package_' . $itemData['package_id'] . '_' . uniqid();
                
            default:
                return uniqid('item_');
        }
    }
    
    /**
     * Update item quantity
     */
    public function updateQuantity(string $itemId, int $quantity): bool
    {
        $cart = $this->getItems();
        
        if (isset($cart[$itemId])) {
            if ($quantity <= 0) {
                return $this->removeItem($itemId);
            }
            
            $cart[$itemId]['quantity'] = $quantity;
            $cart[$itemId]['updated_at'] = now();
            Session::put($this->sessionKey, $cart);
            Session::save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Remove item from cart
     */
    public function removeItem(string $itemId): bool
    {
        $cart = $this->getItems();
        
        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put($this->sessionKey, $cart);
            Session::save();
            return true;
        }
        
        return false;
    }
    
    /**
     * Clear entire cart
     */
    public function clearCart(): void
    {
        Session::forget($this->sessionKey);
        Session::forget('cart_discount');
        Session::forget('cart_coupon');
        Session::save();
    }
    
    /**
     * Update event date for all cart items
     */
    public function updateEventDate(string $eventDate): void
    {
        $cart = $this->getItems();
        
        foreach ($cart as &$item) {
            $item['event_date'] = $eventDate;
            $item['updated_at'] = now();
        }
        
        Session::put($this->sessionKey, $cart);
        Session::save();
    }
    
    /**
     * Get cart item count
     */
    public function getItemCount(): int
    {
        $count = 0;
        foreach ($this->getItems() as $item) {
            if ($item['type'] === 'service_provider') {
                $count++; // Service providers always count as 1
            } else {
                $count += $item['quantity'] ?? 1;
            }
        }
        return $count;
    }
    
    /**
     * Get cart subtotal
     */
    public function getSubtotal(): float
    {
        $subtotal = 0;
        
        foreach ($this->getItems() as $item) {
            switch ($item['type']) {
                case 'product':
                    $itemTotal = $item['price'] * $item['quantity'] * ($item['rental_days'] ?? 1);
                    break;
                    
                case 'service_provider':
                    $itemTotal = $item['price'];
                    break;
                    
                case 'package':
                    $itemTotal = $item['price'] * ($item['quantity'] ?? 1);
                    break;
                    
                default:
                    $itemTotal = $item['price'] * ($item['quantity'] ?? 1);
            }
            
            $subtotal += $itemTotal;
        }
        
        return $subtotal;
    }
    
    /**
     * Calculate tax
     */
    public function getTax(float $amount = null): float
    {
        // Implement your tax calculation logic
        // For now, return 0 (no tax)
        return 0;
    }
    
    /**
     * Apply coupon code
     */
    public function applyCoupon(string $code): array
    {
        // Find coupon
        $coupon = Coupon::where('code', strtoupper($code))
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where(function ($query) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->first();
        
        if (!$coupon) {
            return [
                'success' => false,
                'message' => 'Invalid coupon code.'
            ];
        }
        
        // Check usage limit
        if ($coupon->usage_limit && $coupon->times_used >= $coupon->usage_limit) {
            return [
                'success' => false,
                'message' => 'This coupon has reached its usage limit.'
            ];
        }
        
        // Check minimum amount
        $subtotal = $this->getSubtotal();
        if ($coupon->minimum_amount && $subtotal < $coupon->minimum_amount) {
            return [
                'success' => false,
                'message' => 'Minimum order amount of LKR ' . number_format($coupon->minimum_amount, 2) . ' required.'
            ];
        }
        
        // Calculate discount
        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = $subtotal * ($coupon->discount_value / 100);
            if ($coupon->maximum_discount) {
                $discount = min($discount, $coupon->maximum_discount);
            }
        } else {
            $discount = min($coupon->discount_value, $subtotal);
        }
        
        // Apply coupon
        Session::put('cart_coupon', $code);
        Session::put('cart_discount', $discount);
        Session::save();
        
        return [
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $discount
        ];
    }
    
    /**
     * Remove coupon
     */
    public function removeCoupon(): void
    {
        Session::forget('cart_coupon');
        Session::forget('cart_discount');
        Session::save();
    }
    
    /**
     * Get cart total
     */
    public function getTotal(): float
    {
        $cart = $this->getCart();
        return $cart['total'];
    }
}