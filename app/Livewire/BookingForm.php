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
    
    protected $listeners = ['variationSelected' => 'handleVariationSelected'];
    
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
    
    public function handleVariationSelected($variation)
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
        $maxQuantity = $this->product->max_quantity ?? 10;
        if ($this->quantity < $maxQuantity) {
            $this->quantity++;
            $this->updatedQuantity();
        }
    }
    
    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
            $this->updatedQuantity();
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
    
    // Calculate rental days
    $startDate = Carbon::parse($this->startDate);
    $endDate = Carbon::parse($this->endDate);
    $rentalDays = $startDate->diffInDays($endDate) + 1;
    
    // Prepare cart item data as array
    $itemData = [
        'type' => 'product',
        'product_id' => $this->product->id,
        'variation_id' => $this->selectedVariation ? $this->selectedVariation['id'] : null,
        'name' => $this->product->name . ($this->selectedVariation ? ' - ' . $this->selectedVariation['name'] : ''),
        'price' => $this->selectedVariation ? $this->selectedVariation['price'] : $this->product->base_price,
        'quantity' => $this->quantity,
        'rental_days' => $rentalDays,
        'event_date' => $this->startDate,
        'image' => $this->product->main_image_url ?? $this->product->image ?? 'https://via.placeholder.com/300',
    ];
    
    // Add item to cart
    $cartService->addItem($itemData);
    
    // Dispatch events
    $this->dispatch('cartUpdated');
    $this->dispatch('itemAddedToCart');
    
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