<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CartService;
use Livewire\Attributes\On;

class CartDropdown extends Component
{
    public bool $isOpen = false;
    public array $cartItems = [];
    public float $cartTotal = 0;
    public int $cartCount = 0;
    
    protected $listeners = [
        'cartUpdated' => 'refreshCart',
        'itemAddedToCart' => 'handleItemAdded'
    ];
    
    public function mount()
    {
        $this->refreshCart();
    }
    
    #[On('cartUpdated')]
    #[On('itemAddedToCart')]
    public function refreshCart()
    {
        $cartService = app(CartService::class);
        
        // Get ALL items, not limited
        $this->cartItems = $cartService->getItems();
        $this->cartTotal = $cartService->getTotal();
        $this->cartCount = $cartService->getItemCount();
    }
    
    #[On('itemAddedToCart')]
    public function handleItemAdded()
    {
        $this->refreshCart();
        $this->isOpen = true;
        
        // Auto-close after 3 seconds
        $this->dispatch('item-added-to-cart');
    }
    
    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
    }
    
    public function removeItem(string $itemId)
    {
        $cartService = app(CartService::class);
        $cartService->removeItem($itemId);
        
        $this->refreshCart();
        
        if ($this->cartCount === 0) {
            $this->isOpen = false;
        }
        
        // Notify other components
        $this->dispatch('cartUpdated');
    }
    
    public function render()
    {
        return view('livewire.cart-dropdown');
    }
}