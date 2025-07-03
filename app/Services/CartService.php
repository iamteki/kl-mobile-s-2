<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

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
     * Get cart summary (alias for getSummary)
     */
    public function getCart(): array
    {
        return $this->getSummary();
    }
    
    /**
     * Add item to cart
     */
    public function addItem(array $itemData): string
    {
        $cart = $this->getItems();
        $cartItemId = $this->generateCartItemId($itemData);
        
        if ($itemData['type'] === 'product' && isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $itemData['quantity'];
        } else {
            $itemData['id'] = $cartItemId;
            $itemData['added_at'] = now();
            $cart[$cartItemId] = $itemData;
        }
        
        Session::put($this->sessionKey, $cart);
        
        return $cartItemId;
    }
    
    /**
     * Generate unique cart item ID
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
            $cart[$itemId]['quantity'] = max(1, $quantity); // Ensure quantity is at least 1
            Session::put($this->sessionKey, $cart);
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
    }
    
    /**
     * Get cart subtotal
     */
    public function getSubtotal(): float
    {
        $subtotal = 0;
        
        foreach ($this->getItems() as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            
            if ($item['type'] === 'product' && isset($item['rental_days'])) {
                $itemTotal *= max(1, $item['rental_days']); // Ensure at least 1 day
            }
            
            $subtotal += $itemTotal;
        }
        
        return round($subtotal, 2);
    }
    
    /**
     * Get tax amount
     */
    public function getTax(): float
    {
        $taxRate = config('site.business.tax_rate', 0) / 100;
        return round($this->getSubtotal() * $taxRate, 2);
    }
    
    /**
     * Get total amount
     */
    public function getTotal(): float
    {
        return round($this->getSubtotal() + $this->getTax(), 2);
    }
    
    /**
     * Get total item count
     */
    public function getItemCount(): int
    {
        $count = 0;
        
        foreach ($this->getItems() as $item) {
            $count += $item['quantity'];
        }
        
        return $count;
    }
    
    /**
     * Check if cart has items
     */
    public function hasItems(): bool
    {
        return !empty($this->getItems());
    }
    
    /**
     * Get cart summary
     */
    public function getSummary(): array
    {
        return [
            'items' => $this->getItems(),
            'item_count' => $this->getItemCount(),
            'subtotal' => $this->getSubtotal(),
            'tax' => $this->getTax(),
            'total' => $this->getTotal(),
        ];
    }
    
    /**
     * Validate cart items availability
     */
    public function validateAvailability(): array
    {
        $errors = [];
        $cart = $this->getItems();
        
        foreach ($cart as $itemId => $item) {
            // Implement actual validation logic here
            // This is just a placeholder structure
            switch ($item['type']) {
                case 'product':
                    if (!isset($item['product_id']) || empty($item['product_id'])) {
                        $errors[$itemId] = 'Invalid product';
                    }
                    break;
                    
                case 'service_provider':
                    if (!isset($item['provider_id']) || empty($item['provider_id'])) {
                        $errors[$itemId] = 'Invalid service provider';
                    }
                    break;
                    
                case 'package':
                    if (!isset($item['package_id']) || empty($item['package_id'])) {
                        $errors[$itemId] = 'Invalid package';
                    }
                    break;
            }
        }
        
        return $errors;
    }
}