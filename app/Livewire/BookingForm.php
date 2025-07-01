<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Services\CartService;
use App\Services\AvailabilityService;
use App\Services\PricingService;

class BookingForm extends Component
{
    public $product;
    public $startDate;
    public $endDate;
    public $quantity = 1;
    public $selectedVariation = null;
    public $selectedAddons = [];
    public $calculatedPrice = null;
    public $availabilityMessage = '';
    public $isAvailable = false;
    
    protected $listeners = ['variationSelected'];
    
    protected $rules = [
        'startDate' => 'required|date|after:today',
        'endDate' => 'required|date|after_or_equal:startDate',
        'quantity' => 'required|integer|min:1'
    ];
    
    public function mount($product)
    {
        $this->product = $product;
        $this->startDate = Carbon::tomorrow()->format('Y-m-d');
        $this->endDate = Carbon::tomorrow()->format('Y-m-d');
    }
    
    public function variationSelected($variation)
    {
        $this->selectedVariation = $variation;
        $this->calculatePrice();
    }
    
    public function updatedStartDate()
    {
        if ($this->endDate < $this->startDate) {
            $this->endDate = $this->startDate;
        }
        $this->calculatePrice();
        $this->checkAvailability();
    }
    
    public function updatedEndDate()
    {
        $this->calculatePrice();
        $this->checkAvailability();
    }
    
    public function updatedQuantity()
    {
        $this->calculatePrice();
        $this->checkAvailability();
    }
    
    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->max_quantity) {
            $this->quantity++;
        }
    }
    
    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }
    
    public function calculatePrice()
    {
        if (!$this->startDate || !$this->endDate) {
            return;
        }
        
        $days = Carbon::parse($this->startDate)->diffInDays(Carbon::parse($this->endDate)) + 1;
        $basePrice = $this->selectedVariation 
            ? $this->selectedVariation['price'] 
            : $this->product->base_price;
        
        // Apply tier pricing
        if ($days >= 6) {
            $pricePerDay = $basePrice * 0.8; // 20% off
        } elseif ($days >= 3) {
            $pricePerDay = $basePrice * 0.9; // 10% off
        } else {
            $pricePerDay = $basePrice;
        }
        
        $subtotal = $pricePerDay * $days * $this->quantity;
        
        $this->calculatedPrice = [
            'days' => $days,
            'price_per_day' => $pricePerDay,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'savings' => $basePrice != $pricePerDay ? round((1 - $pricePerDay / $basePrice) * 100) : 0
        ];
    }
    
    public function checkAvailability()
    {
        if (!$this->startDate || !$this->endDate || !$this->quantity) {
            return;
        }
        
        // Simple availability check (in real app, this would check bookings)
        $availableQty = $this->selectedVariation 
            ? $this->selectedVariation['quantity'] 
            : $this->product->available_quantity;
        
        $this->isAvailable = $availableQty >= $this->quantity;
        
        if ($this->isAvailable) {
            $this->availabilityMessage = 'Equipment is available for your dates!';
        } else {
            $this->availabilityMessage = 'Sorry, only ' . $availableQty . ' units available for these dates.';
        }
    }
    
    public function addToCart()
    {
        $this->validate();
        
        if (!$this->isAvailable) {
            $this->addError('availability', 'Equipment is not available for the selected dates.');
            return;
        }
        
        $cartService = app(CartService::class);
        
        $cartService->addItem(
            $this->product->id,
            $this->quantity,
            $this->selectedVariation ? $this->selectedVariation['id'] : null,
            $this->startDate
        );
        
        // Update event details in cart
        $cartService->updateEventDetails($this->startDate);
        
        // Emit event to update cart dropdown
        $this->emit('cartUpdated');
        $this->emit('itemAddedToCart');
        
        // Show success message
        session()->flash('success', 'Item added to cart successfully!');
    }
    
    public function bookNow()
    {
        $this->addToCart();
        
        // Redirect to checkout
        return redirect()->route('checkout.event-details');
    }
    
    public function render()
    {
        return view('livewire.booking-form');
    }
}