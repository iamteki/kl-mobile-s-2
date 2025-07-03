<div class="cart-dropdown-wrapper">
    <!-- Cart Button -->
    <button class="btn btn-outline-primary position-relative" 
            wire:click="toggleCart"
            type="button">
        <i class="fas fa-shopping-cart"></i>
        <span class="d-none d-md-inline ms-1">Cart</span>
        @if($cartCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $cartCount }}
                <span class="visually-hidden">items in cart</span>
            </span>
        @endif
    </button>
    
    <!-- Cart Dropdown -->
    <div class="cart-dropdown {{ $isOpen ? 'show' : '' }}" 
         wire:ignore.self
         x-data="{ open: @entangle('isOpen') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="open = false"
         style="display: none;">
        
        <div class="cart-dropdown-header">
            <h5 class="mb-0">Shopping Cart ({{ $cartCount }})</h5>
            <button type="button" 
                    class="btn-close btn-close-white" 
                    wire:click="toggleCart"></button>
        </div>
        
        <div class="cart-dropdown-body">
            @if(count($cartItems) > 0)
                <div class="cart-items-list">
                    @foreach($cartItems as $itemId => $item)
                        <div class="cart-dropdown-item">
                            <div class="cart-item-image">
                                <img src="{{ $item['image'] ?? 'https://via.placeholder.com/60' }}" 
                                     alt="{{ $item['name'] }}">
                            </div>
                            <div class="cart-item-details">
                                <h6 class="cart-item-name">{{ Str::limit($item['name'], 30) }}</h6>
                                <p class="cart-item-meta mb-0">
                                    @if($item['type'] === 'product')
                                        Qty: {{ $item['quantity'] }} × {{ $item['rental_days'] ?? 1 }} days
                                    @elseif($item['type'] === 'service_provider')
                                        {{ date('M d', strtotime($item['event_date'])) }} at {{ date('h:i A', strtotime($item['start_time'])) }}
                                    @endif
                                </p>
                                <p class="cart-item-price mb-0">
                                    LKR {{ number_format($item['price'] * $item['quantity'] * ($item['rental_days'] ?? 1)) }}
                                </p>
                            </div>
                            <button class="btn btn-sm btn-link text-danger p-0" 
                                    wire:click="removeItem('{{ $itemId }}')"
                                    wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                
                <div class="cart-dropdown-footer">
                    <div class="cart-total">
                        <span>Total:</span>
                        <strong>LKR {{ number_format($cartTotal) }}</strong>
                    </div>
                    <div class="cart-actions">
                        <a href="{{ route('cart.index') }}" 
                           class="btn btn-outline-primary btn-sm">
                            View Cart
                        </a>
                        <a href="{{ route('checkout.event-details') }}" 
                           class="btn btn-primary btn-sm">
                            Checkout
                        </a>
                    </div>
                </div>
            @else
                <div class="empty-cart-message text-center py-4">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Your cart is empty</p>
                    <a href="{{ route('home') }}" 
                       class="btn btn-primary btn-sm mt-3"
                       wire:click="toggleCart">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
        
        <div wire:loading class="cart-loading">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.cart-dropdown-wrapper {
    position: relative;
}

.cart-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 380px;
    max-width: 90vw;
    background: var(--bg-card);
    border: 1px solid var(--border-dark);
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    z-index: 1050;
    margin-top: 10px;
}

.cart-dropdown.show {
    display: block !important;
}

.cart-dropdown-header {
    padding: 20px;
    border-bottom: 1px solid var(--border-dark);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-dropdown-header h5 {
    color: var(--text-light);
    font-weight: 600;
}

.cart-dropdown-body {
    max-height: 400px;
    overflow-y: auto;
    position: relative;
}

.cart-dropdown-body::-webkit-scrollbar {
    width: 6px;
}

.cart-dropdown-body::-webkit-scrollbar-track {
    background: var(--bg-darker);
}

.cart-dropdown-body::-webkit-scrollbar-thumb {
    background: var(--primary-purple);
    border-radius: 3px;
}

.cart-items-list {
    padding: 15px;
}

.cart-dropdown-item {
    display: flex;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid var(--border-dark);
}

.cart-dropdown-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 60px;
    height: 60px;
    flex-shrink: 0;
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.cart-item-details {
    flex: 1;
    min-width: 0;
}

.cart-item-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 5px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cart-item-meta {
    font-size: 12px;
    color: var(--text-gray);
}

.cart-item-price {
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-purple);
}

.cart-dropdown-footer {
    padding: 20px;
    border-top: 1px solid var(--border-dark);
}

.cart-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 16px;
    color: var(--text-light);
}

.cart-total strong {
    color: var(--primary-purple);
    font-size: 18px;
}

.cart-actions {
    display: flex;
    gap: 10px;
}

.cart-actions .btn {
    flex: 1;
}

.empty-cart-message {
    padding: 40px 20px;
}

.cart-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
}

/* Mobile Responsive */
@media (max-width: 576px) {
    .cart-dropdown {
        width: 320px;
        right: -10px;
    }
    
    .cart-dropdown-body {
        max-height: 300px;
    }
    
    .cart-actions {
        flex-direction: column;
    }
    
    .cart-actions .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto close cart dropdown after 3 seconds when item is added
Livewire.on('closeCartDropdown', () => {
    setTimeout(() => {
        Livewire.dispatch('toggleCart');
    }, 3000);
});

// Listen for cart updates from other components
document.addEventListener('cartUpdated', () => {
    Livewire.dispatch('cartUpdated');
});
</script>
@endpush