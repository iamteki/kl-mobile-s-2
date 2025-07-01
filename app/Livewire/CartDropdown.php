<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;

class CartDropdown extends Component
{
    public $cartItems = [];
    public $cartTotal = 0;
    public $cartCount = 0;
    public $isOpen = false;
    
    protected $listeners = [
        'cartUpdated' => 'refreshCart',
        'itemAddedToCart' => 'handleItemAdded'
    ];
    
    public function mount()
    {
        $this->refreshCart();
    }
    
    public function refreshCart()
    {
        $cart = app(CartService::class)->getCart();
        
        $this->cartItems = $cart['items'] ?? [];
        $this->cartTotal = $cart['total'] ?? 0;
        $this->cartCount = $cart['count'] ?? 0;
    }
    
    public function handleItemAdded()
    {
        $this->refreshCart();
        $this->isOpen = true;
        
        // Auto close after 3 seconds
       $this->dispatch('cart-dropdown-opened');
    }
    
    public function removeItem($itemId)
    {
        app(CartService::class)->removeItem($itemId);
        $this->refreshCart();
        
       $this->dispatch('cartUpdated');
        
        if ($this->cartCount === 0) {
            $this->isOpen = false;
        }
    }
    
    public function updateQuantity($itemId, $quantity)
    {
        if ($quantity < 1) {
            $this->removeItem($itemId);
            return;
        }
        
        app(CartService::class)->updateQuantity($itemId, $quantity);
        $this->refreshCart();
        
        $this->emit('cartUpdated');
    }
    
    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
    }
    
    public function render()
    {
        return view('livewire.cart-dropdown');
    }
}