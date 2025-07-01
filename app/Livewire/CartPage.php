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
        
        if (count($this->cart['items']) === 0) {
            return redirect()->route('cart.index');
        }
    }
    
    public function clearCart()
    {
        $cartService = app(CartService::class);
        $cartService->clearCart();
        
        return redirect()->route('cart.index');
    }
    
    public function applyCoupon()
    {
        if (empty($this->couponCode)) {
            $this->couponMessage = 'Please enter a coupon code.';
            return;
        }
        
        // Mock coupon validation
        if (strtoupper($this->couponCode) === 'SAVE10') {
            $this->couponApplied = true;
            $this->couponMessage = 'Coupon applied successfully! 10% discount added.';
            $this->cart['discount'] = $this->cart['total'] * 0.1;
            $this->cart['coupon'] = $this->couponCode;
        } else {
            $this->couponApplied = false;
            $this->couponMessage = 'Invalid coupon code.';
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
        // Validate cart availability before checkout
        if (auth()->check()) {
            return redirect()->route('checkout.event-details');
        } else {
            session(['url.intended' => route('checkout.event-details')]);
            return redirect()->route('login');
        }
    }
    
    public function render()
    {
        return view('livewire.cart-page');
    }
}