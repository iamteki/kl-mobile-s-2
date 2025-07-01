<div> <!-- Single root element wrapper -->
    <div class="booking-section">
        <h5 class="mb-3">Book This Equipment</h5>
        
        <div class="date-inputs">
            <div class="form-group">
                <label>Rental Start Date</label>
                <input type="date" 
                       class="form-control" 
                       wire:model.live="startDate"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                @error('startDate') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Rental End Date</label>
                <input type="date" 
                       class="form-control" 
                       wire:model.live="endDate"
                       min="{{ $startDate }}">
                @error('endDate') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="quantity-selector">
            <label>Quantity:</label>
            <div class="quantity-controls">
                <button class="qty-btn" wire:click="decrementQuantity">
                    <i class="fas fa-minus"></i>
                </button>
                <input type="number" 
                       class="qty-input" 
                       wire:model.live="quantity" 
                       min="1" 
                       max="{{ $product->max_quantity ?? 10 }}">
                <button class="qty-btn" wire:click="incrementQuantity">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <span class="max-available">({{ $product->available_quantity }} available)</span>
        </div>
        
        @if($calculatedPrice)
            <div class="price-summary mt-4 p-3 bg-dark rounded">
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ $calculatedPrice['days'] }} {{ Str::plural('day', $calculatedPrice['days']) }} × {{ $quantity }} {{ Str::plural('unit', $quantity) }}</span>
                    <span>LKR {{ number_format($calculatedPrice['price_per_day']) }}/day</span>
                </div>
                @if(isset($calculatedPrice['savings']) && $calculatedPrice['savings'] > 0)
                    <div class="text-success small mb-2">
                        <i class="fas fa-tag me-1"></i>{{ $calculatedPrice['savings'] }}% discount applied
                    </div>
                @endif
                <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                    <strong>Total:</strong>
                    <strong class="text-primary">LKR {{ number_format($calculatedPrice['total']) }}</strong>
                </div>
            </div>
        @endif
        
        @if($availabilityMessage)
            <div class="alert alert-{{ $isAvailable ? 'success' : 'warning' }} mt-3">
                <i class="fas fa-{{ $isAvailable ? 'check' : 'exclamation' }}-circle me-2"></i>
                {{ $availabilityMessage }}
            </div>
        @endif
        
        @error('availability')
            <div class="alert alert-danger mt-3">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
            </div>
        @enderror
        
        @if (session()->has('success'))
            <div class="alert alert-success mt-3">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Action Buttons (inside the root div) -->
    <div class="action-buttons">
        <button class="btn btn-primary" 
                wire:click="addToCart"
                wire:loading.attr="disabled"
                {{ !$isAvailable ? 'disabled' : '' }}>
            <span wire:loading.remove wire:target="addToCart">
                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
            </span>
            <span wire:loading wire:target="addToCart">
                <i class="fas fa-spinner fa-spin me-2"></i>Adding...
            </span>
        </button>
        
        <button class="btn-outline" 
                wire:click="bookNow"
                wire:loading.attr="disabled"
                {{ !$isAvailable ? 'disabled' : '' }}>
            <span wire:loading.remove wire:target="bookNow">
                Book Now
            </span>
            <span wire:loading wire:target="bookNow">
                Processing...
            </span>
        </button>
        
        <button class="btn-icon" title="Add to Wishlist">
            <i class="far fa-heart"></i>
        </button>
    </div>

    <style>
    /* Booking Section */
    .booking-section {
        background: var(--bg-card);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid var(--border-dark);
    }

    .date-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 14px;
        color: var(--text-gray);
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        color: var(--text-light);
        padding: 12px;
        border-radius: 8px;
    }

    .form-control:focus {
        background: var(--bg-dark);
        border-color: var(--primary-purple);
        color: var(--text-light);
        box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qty-btn {
        width: 40px;
        height: 40px;
        border: 1px solid var(--border-dark);
        background: var(--bg-dark);
        color: var(--text-light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .qty-btn:hover {
        border-color: var(--primary-purple);
        color: var(--primary-purple);
    }

    .qty-input {
        width: 60px;
        text-align: center;
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        color: var(--text-light);
        padding: 8px;
        border-radius: 8px;
    }

    .max-available {
        color: var(--text-gray);
        font-size: 14px;
    }

    .price-summary {
        font-size: 14px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
        border: none;
        padding: 15px 40px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 8px;
        flex: 1;
    }

    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(147, 51, 234, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--primary-purple);
        color: var(--primary-purple);
        padding: 15px 40px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-outline:hover:not(:disabled) {
        background: var(--primary-purple);
        color: white;
    }

    .btn-icon {
        width: 56px;
        height: 56px;
        border: 2px solid var(--border-dark);
        background: var(--bg-card);
        color: var(--text-light);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-icon:hover {
        border-color: var(--primary-purple);
        color: var(--primary-purple);
    }

    @media (max-width: 768px) {
        .date-inputs {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-icon {
            width: 100%;
        }
    }
    </style>
</div>