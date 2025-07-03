<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;

class CartPage extends Component
{
    public $cart = [];
    public $couponCode = '';
    public $couponApplied = false;
    public $couponMessage = '';
    
    protected $listeners = ['cartUpdated' => 'refreshCart'];
    
    public function mount()
    {
        $this->refreshCart();
    }
    
    public function refreshCart()
    {
        $cartService = app(CartService::class);
        $this->cart = $cartService->getCart();
        
        // Check if coupon is applied
        if ($this->cart['coupon']) {
            $this->couponApplied = true;
            $this->couponCode = $this->cart['coupon'];
        }
    }
    
    public function updateQuantity($itemId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeItem($itemId);
            return;
        }
        
        $cartService = app(CartService::class);
        $cartService->updateQuantity($itemId, $quantity);
        
        $this->refreshCart();
        $this->dispatch('cartUpdated');
    }
    
    public function removeItem($itemId)
    {
        $cartService = app(CartService::class);
        $cartService->removeItem($itemId);
        
        $this->refreshCart();
        $this->dispatch('cartUpdated');
        
        // Show success message
        session()->flash('success', 'Item removed from cart');
        
        // If cart is empty, redirect
        if (count($this->cart['items']) === 0) {
            return redirect()->route('home')->with('info', 'Your cart is empty');
        }
    }
    
    public function clearCart()
    {
        $cartService = app(CartService::class);
        $cartService->clearCart();
        
        return redirect()->route('home')->with('info', 'Cart cleared');
    }
    
    public function applyCoupon()
    {
        if (empty($this->couponCode)) {
            $this->couponMessage = 'Please enter a coupon code.';
            $this->couponApplied = false;
            return;
        }
        
        $cartService = app(CartService::class);
        $result = $cartService->applyCoupon($this->couponCode);
        
        $this->couponMessage = $result['message'];
        $this->couponApplied = $result['success'];
        
        if ($result['success']) {
            $this->refreshCart();
        }
    }
    
    public function removeCoupon()
    {
        $cartService = app(CartService::class);
        $cartService->removeCoupon();
        
        $this->couponCode = '';
        $this->couponApplied = false;
        $this->couponMessage = '';
        $this->refreshCart();
    }
    
    public function proceedToCheckout()
    {
        // Validate cart has items
        if (count($this->cart['items']) === 0) {
            return redirect()->route('home')->with('error', 'Your cart is empty');
        }
        
        // Check if user is logged in
        if (auth()->check()) {
            return redirect()->route('checkout.event-details');
        } else {
            session(['url.intended' => route('checkout.event-details')]);
            return redirect()->route('login')->with('info', 'Please login to continue with checkout');
        }
    }
    
    public function render()
    {
        return view('livewire.cart-page');
    }
}