<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;

class CartDropdown extends Component
{
    public $isOpen = false;
    public $cartItems = [];
    public $cartTotal = 0;
    public $cartCount = 0;
    
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
        $cartService = app(CartService::class);
        
        $this->cartItems = $cartService->getItems();
        $this->cartTotal = $cartService->getTotal();
        $this->cartCount = $cartService->getItemCount();
    }
    
    public function handleItemAdded()
    {
        $this->refreshCart();
        $this->isOpen = true;
        
        // Auto close after 3 seconds
        $this->dispatch('closeCartDropdown');
    }
    
    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
    }
    
    public function removeItem($itemId)
    {
        app(CartService::class)->removeItem($itemId);
        $this->refreshCart();
        
        if ($this->cartCount === 0) {
            $this->isOpen = false;
        }
    }
    
    public function render()
    {
        return view('livewire.cart-dropdown');
    }
}