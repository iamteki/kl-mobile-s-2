<div> <!-- Single root element for Livewire -->
    <form wire:submit.prevent="search" class="search-form">
        <div class="row g-3">
            <div class="col-md-3">
                <select wire:model="category" class="form-select">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" 
                       wire:model="eventDate" 
                       class="form-control" 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       placeholder="Event Date">
            </div>
            <div class="col-md-3">
                <input type="number" 
                       wire:model="numberOfGuests" 
                       class="form-control" 
                       placeholder="Number of Guests"
                       min="1">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <span wire:loading.remove wire:target="search">
                        <i class="fas fa-search me-2"></i>Search
                    </span>
                    <span wire:loading wire:target="search">
                        <i class="fas fa-spinner fa-spin me-2"></i>Searching...
                    </span>
                </button>
            </div>
        </div>
        
        <!-- Advanced Search -->
        <div class="row mt-3">
            <div class="col-md-9">
                <input type="text" 
                       wire:model="searchTerm" 
                       class="form-control" 
                       placeholder="Or search by equipment name...">
            </div>
            <div class="col-md-3">
                @if($showResults)
                    <button type="button" wire:click="clearSearch" class="btn btn-outline-light w-100">
                        <i class="fas fa-times me-2"></i>Clear
                    </button>
                @endif
            </div>
        </div>
        
        @error('search')
            <div class="alert alert-warning mt-3 mb-0">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
            </div>
        @enderror
        
        @error('eventDate')
            <div class="alert alert-danger mt-3 mb-0">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
            </div>
        @enderror
    </form>
    
    <!-- Search Results -->
    @if($showResults && count($searchResults) > 0)
        <div class="search-results mt-5">
            <h5 class="text-white mb-4">
                Found {{ count($searchResults) }} items available for {{ Carbon\Carbon::parse($eventDate)->format('M d, Y') }}
            </h5>
            
            <div class="row g-4">
                @foreach($searchResults as $product)
                    <div class="col-lg-3 col-md-6">
                        <x-product-card :product="$product" :show-category="false" />
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                    View All Results <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    @elseif($showResults)
        <div class="alert alert-info mt-5">
            <i class="fas fa-info-circle me-2"></i>
            No equipment found for your search criteria. Try different filters or 
            <a href="{{ route('contact') }}" class="alert-link">contact us</a> for assistance.
        </div>
    @endif

    <style>
    .search-form input, .search-form select {
        background-color: var(--bg-dark);
        border: 1px solid var(--border-dark);
        color: var(--text-light);
        padding: 12px;
        font-size: 16px;
    }

    .search-form input:focus, .search-form select:focus {
        background-color: var(--bg-dark);
        border-color: var(--primary-purple);
        color: var(--text-light);
        box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
    }

    .search-form option {
        background-color: var(--bg-dark);
    }

    .search-results {
        animation: fadeInUp 0.5s ease-out;
    }
    </style>
</div>