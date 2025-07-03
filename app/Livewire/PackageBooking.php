<?php

namespace App\Http\Livewire;

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

    public function addToCart()
    {
        if ($this->showBookingForm) {
            $this->validate();
        }

        $this->isProcessing = true;

        try {
            $cartService = app(CartService::class);
            
            $itemData = [
                'type' => 'package',
                'id' => $this->package->id,
                'name' => $this->package->name,
                'price' => $this->package->price,
                'quantity' => 1,
                'image' => $this->package->image,
                'attributes' => [
                    'event_date' => $this->eventDate,
                    'event_type' => $this->eventType,
                    'venue_address' => $this->venueAddress,
                    'attendees' => $this->attendees,
                    'notes' => $this->notes,
                    'service_duration' => $this->package->service_duration ?? 8
                ]
            ];

            $cartService->add($itemData);

            // Emit event for cart update
            $this->emit('cartUpdated');
            
            // Show success message
            session()->flash('success', 'Package added to cart successfully!');
            
            // Reset form
            $this->reset(['eventDate', 'eventType', 'venueAddress', 'attendees', 'notes', 'showBookingForm']);
            
            // Optionally redirect to cart
            // return redirect()->route('cart.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error adding package to cart. Please try again.');
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.package-booking');
    }
}