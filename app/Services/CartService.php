<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'cart';
    
    /**
     * Get all cart items (no limit)
     */
    public function getItems(): array
    {
        return Session::get($this->sessionKey, []);
    }
    
    /**
     * Get cart summary with all items
     */
    public function getCart(): array
    {
        $items = $this->getItems();
        
        return [
            'items' => $items, // Return ALL items, not limited
            'count' => $this->getItemCount(),
            'subtotal' => $this->getSubtotal(),
            'tax' => $this->getTax(),
            'total' => $this->getTotal(),
            'discount' => Session::get('cart_discount', 0),
            'coupon' => Session::get('cart_coupon', null)
        ];
    }
    
    /**
     * Add item to cart - improved to prevent duplicates and handle variations better
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
        
        // Save the entire cart back to session
        Session::put($this->sessionKey, $cart);
        Session::save(); // Force session save
        
        return $cartItemId;
    }
    
    /**
     * Generate unique cart item ID based on item properties
     */
    protected function generateCartItemId(array $itemData): string
    {
        switch ($itemData['type']) {
            case 'product':
                return 'product_' . $itemData['product_id'] . '_' . ($itemData['variation_id'] ?? 0) . '_' . strtotime($itemData['event_date']);
                
            case 'service_provider':
                return 'service_' . $itemData['provider_id'] . '_' . ($itemData['pricing_tier_id'] ?? 0) . '_' . strtotime($itemData['event_date'] . ' ' . $itemData['start_time']);
                
            case 'package':
                return 'package_' . $itemData['package_id'] . '_' . strtotime($itemData['event_date']);
                
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
                    $itemTotal = $item['price'] * $item['quantity'];
                    break;
                    
                default:
                    $itemTotal = $item['price'] * ($item['quantity'] ?? 1);
            }
            
            $subtotal += $itemTotal;
        }
        
        return $subtotal;
    }
    
    /**
     * Get tax amount (15% VAT)
     */
    public function getTax(): float
    {
        return $this->getSubtotal() * 0.15;
    }
    
    /**
     * Get total amount
     */
    public function getTotal(): float
    {
        $subtotal = $this->getSubtotal();
        $tax = $this->getTax();
        $discount = Session::get('cart_discount', 0);
        
        return ($subtotal + $tax) - $discount;
    }
    
    /**
     * Apply coupon code
     */
    public function applyCoupon(string $code): array
    {
        // Mock coupon validation - replace with actual implementation
        $validCoupons = [
            'SAVE10' => ['type' => 'percentage', 'value' => 10],
            'SAVE50' => ['type' => 'fixed', 'value' => 50],
            'FIRSTORDER' => ['type' => 'percentage', 'value' => 15],
        ];
        
        $code = strtoupper($code);
        
        if (isset($validCoupons[$code])) {
            $coupon = $validCoupons[$code];
            
            if ($coupon['type'] === 'percentage') {
                $discount = $this->getSubtotal() * ($coupon['value'] / 100);
            } else {
                $discount = $coupon['value'];
            }
            
            Session::put('cart_coupon', $code);
            Session::put('cart_discount', $discount);
            Session::save();
            
            return [
                'success' => true,
                'message' => "Coupon {$code} applied successfully!",
                'discount' => $discount
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Invalid coupon code'
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
}