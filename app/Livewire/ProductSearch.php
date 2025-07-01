<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;

class ProductSearch extends Component
{
    public $category = '';
    public $eventDate = '';
    public $numberOfGuests = '';
    public $searchTerm = '';
    
    public $categories = [];
    public $searchResults = [];
    public $showResults = false;
    
    protected $rules = [
        'eventDate' => 'required|date|after:today',
        'numberOfGuests' => 'nullable|integer|min:1',
    ];
    
    public function mount()
    {
        $this->categories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->get();
            
        // Set default event date to tomorrow
        $this->eventDate = Carbon::tomorrow()->format('Y-m-d');
    }
    
    public function search()
    {
        if (empty($this->category) && empty($this->searchTerm)) {
            $this->addError('search', 'Please select a category or enter a search term.');
            return;
        }
        
        $this->validate();
        
        // Build query
        $query = Product::with(['category', 'media'])
            ->where('status', 'active');
        
        // Filter by category
        if ($this->category) {
            $query->where('category_id', $this->category);
        }
        
        // Filter by search term
        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$this->searchTerm}%");
            });
        }
        
        // Check availability for the event date
        if ($this->eventDate) {
            // This would involve checking the inventory and bookings
            // For now, we'll just get all active products
        }
        
        $this->searchResults = $query->limit(12)->get();
        $this->showResults = true;
        
        // Emit event for analytics
        $this->emit('searchPerformed', [
            'category' => $this->category,
            'date' => $this->eventDate,
            'guests' => $this->numberOfGuests,
            'term' => $this->searchTerm,
        ]);
    }
    
    public function clearSearch()
    {
        $this->reset(['category', 'searchTerm', 'numberOfGuests', 'searchResults', 'showResults']);
        $this->eventDate = Carbon::tomorrow()->format('Y-m-d');
    }
    
    public function render()
    {
        return view('livewire.product-search');
    }
}