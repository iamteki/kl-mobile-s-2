<?php

namespace App\Livewire;  

use Livewire\Component;
use App\Models\Package;
use App\Services\CartService;
use Illuminate\Support\Facades\Session;

class PackageBooking extends Component
{
    public $package;
    public $eventDate;
    public $eventType = '';
    public $venueAddress = '';
    public $attendees = '';
    public $notes = '';
    public $showBookingForm = false;
    public $isProcessing = false;

    protected $rules = [
        'eventDate' => 'required|date|after:today',
        'eventType' => 'required|string|max:255',
        'venueAddress' => 'required|string|max:500',
        'attendees' => 'required|integer|min:1|max:10000',
        'notes' => 'nullable|string|max:1000'
    ];

    protected $messages = [
        'eventDate.required' => 'Please select an event date',
        'eventDate.after' => 'Event date must be in the future',
        'eventType.required' => 'Please specify the type of event',
        'venueAddress.required' => 'Please provide the venue address',
        'attendees.required' => 'Please specify the number of attendees',
        'attendees.min' => 'Number of attendees must be at least 1'
    ];

    public function mount(Package $package)
    {
        $this->package = $package;
    }

    public function showForm()
    {
        $this->showBookingForm = true;
    }

    public function hideForm()
    {
        $this->showBookingForm = false;
        $this->resetValidation();
    }

    public function proceedToBooking()
    {
        if ($this->showBookingForm) {
            $this->validate();
        }

        $this->isProcessing = true;

        try {
            // Store booking details in session for checkout
            session([
                'booking_details' => [
                    'type' => 'package',
                    'package_id' => $this->package->id,
                    'package_name' => $this->package->name,
                    'package_price' => $this->package->price,
                    'event_date' => $this->eventDate,
                    'event_type' => $this->eventType,
                    'venue_address' => $this->venueAddress,
                    'attendees' => $this->attendees,
                    'notes' => $this->notes,
                    'service_duration' => $this->package->service_duration ?? 8,
                    'category' => $this->package->category,
                    'suitable_for' => $this->package->suitable_for,
                    'image' => $this->package->image
                ]
            ]);

            // Redirect to checkout/booking page
            return redirect()->route('checkout.event-details');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error processing booking. Please try again.');
            \Log::error('Package booking error: ' . $e->getMessage());
            $this->isProcessing = false;
        }
    }

    public function quickAddToCart()
    {
        $this->isProcessing = true;

        try {
            $cartService = app(CartService::class);
            
            // Format data for quick add (without event details)
            $itemData = [
                'type' => 'package',
                'package_id' => $this->package->id,
                'name' => $this->package->name,
                'price' => $this->package->price,
                'quantity' => 1,
                'event_date' => now()->addDays(7)->format('Y-m-d'), // Default to 7 days from now
                'image' => $this->package->image,
                'category' => $this->package->category,
                'suitable_for' => $this->package->suitable_for,
                'service_duration' => $this->package->service_duration ?? 8,
                'event_type' => '',
                'venue_address' => '',
                'attendees' => '',
                'notes' => ''
            ];

            // Add to cart
            $cartService->addItem($itemData);

            // Emit events
            $this->dispatch('cartUpdated');
            $this->dispatch('itemAddedToCart');
            
            // Show success message
            session()->flash('success', 'Package added to cart! Please update event details during checkout.');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding package to cart. Please try again.');
            \Log::error('Quick add package error: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.package-booking');
    }
}