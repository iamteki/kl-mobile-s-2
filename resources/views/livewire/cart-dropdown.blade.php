<div>
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
        @if($isOpen)
            <div class="cart-dropdown" 
                 x-data="{ shown: @entangle('isOpen') }"
                 x-show="shown"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 @click.away="$wire.set('isOpen', false)">
                
                <div class="cart-dropdown-inner">
                    <div class="cart-dropdown-header">
                        <h5 class="mb-0">Shopping Cart ({{ $cartCount }})</h5>
                        <button type="button" 
                                class="btn-close btn-close-white" 
                                wire:click="toggleCart"></button>
                    </div>
                    
                    <div class="cart-dropdown-body">
                        @if(count($cartItems) > 0)
                            <div class="cart-items-list">
                                {{-- Show ALL items, not limited --}}
                                @foreach($cartItems as $itemId => $item)
                                    <div class="cart-dropdown-item" wire:key="dropdown-item-{{ $itemId }}">
                                        <div class="cart-item-image">
                                            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/60' }}" 
                                                 alt="{{ $item['name'] }}"
                                                 loading="lazy">
                                        </div>
                                        <div class="cart-item-details">
                                            <h6 class="cart-item-name">{{ Str::limit($item['name'], 30) }}</h6>
                                            <p class="cart-item-meta mb-0">
                                                @if($item['type'] === 'product')
                                                    Qty: {{ $item['quantity'] }} 
                                                    @if(isset($item['rental_days']))
                                                        × {{ $item['rental_days'] }} {{ Str::plural('day', $item['rental_days']) }}
                                                    @endif
                                                @elseif($item['type'] === 'service_provider')
                                                    {{ $item['duration_text'] ?? 'Service' }}
                                                @endif
                                            </p>
                                            <p class="cart-item-price mb-0">
                                                LKR {{ number_format($item['price'] * ($item['quantity'] ?? 1) * ($item['rental_days'] ?? 1)) }}
                                            </p>
                                        </div>
                                        <button wire:click="removeItem('{{ $itemId }}')" 
                                                class="btn btn-link text-danger p-0 ms-2">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- Show view all if many items --}}
                            @if(count($cartItems) > 4)
                                <div class="text-center py-2">
                                    <small class="text-muted">
                                        Showing all {{ count($cartItems) }} items
                                    </small>
                                </div>
                            @endif
                            
                            <div class="cart-dropdown-footer">
                                <div class="cart-total">
                                    <span>Total:</span>
                                    <strong>LKR {{ number_format($cartTotal) }}</strong>
                                </div>
                                <div class="cart-actions">
                                    <a href="{{ route('cart.index') }}" 
                                       wire:click="toggleCart"
                                       class="btn btn-outline-primary">
                                        View Cart
                                    </a>
                                    <a href="{{ route('checkout.event-details') }}" 
                                       class="btn btn-primary">
                                        Checkout
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="empty-cart-message text-center">
                                <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                                <p class="mb-2">Your cart is empty</p>
                                <a href="{{ route('home') }}" 
                                   class="btn btn-primary btn-sm mt-3"
                                   wire:click="toggleCart">
                                    Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <div wire:loading wire:target="removeItem" class="cart-loading">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
    /* Cart dropdown wrapper */
    .cart-dropdown-wrapper {
        position: relative;
        display: inline-block;
    }

    /* Cart dropdown */
    .cart-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 10px;
        width: 420px;
        max-width: 90vw;
        z-index: 1050;
    }

    .cart-dropdown-inner {
        background: var(--bg-card, #141414);
        border: 1px solid var(--border-dark, rgba(255, 255, 255, 0.1));
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
        overflow: hidden;
    }

    /* Header */
    .cart-dropdown-header {
        padding: 20px;
        border-bottom: 1px solid var(--border-dark, rgba(255, 255, 255, 0.1));
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(147, 51, 234, 0.05);
    }

    .cart-dropdown-header h5 {
        color: var(--text-light, #fff);
        font-weight: 600;
        margin: 0;
    }

    /* Body */
    .cart-dropdown-body {
        max-height: 400px;
        overflow-y: auto;
        position: relative;
    }

    /* Custom scrollbar */
    .cart-dropdown-body::-webkit-scrollbar {
        width: 6px;
    }

    .cart-dropdown-body::-webkit-scrollbar-track {
        background: var(--bg-darker, rgba(0, 0, 0, 0.3));
    }

    .cart-dropdown-body::-webkit-scrollbar-thumb {
        background: var(--primary-purple, #9333EA);
        border-radius: 3px;
    }

    /* Items */
    .cart-items-list {
        padding: 15px;
    }

    .cart-dropdown-item {
        display: flex;
        gap: 12px;
        padding: 12px;
        margin-bottom: 8px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 10px;
        align-items: flex-start;
        transition: background 0.2s;
    }

    .cart-dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .cart-dropdown-item:last-child {
        margin-bottom: 0;
    }

    .cart-item-image {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
        border-radius: 8px;
        overflow: hidden;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-details {
        flex: 1;
        min-width: 0;
    }

    .cart-item-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-light, #fff);
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .cart-item-meta {
        font-size: 12px;
        color: var(--text-gray, rgba(255, 255, 255, 0.6));
        line-height: 1.4;
    }

    .cart-item-price {
        font-size: 14px;
        font-weight: 600;
        color: var(--secondary-purple, #C084FC);
    }

    /* Footer */
    .cart-dropdown-footer {
        padding: 20px;
        border-top: 1px solid var(--border-dark, rgba(255, 255, 255, 0.1));
        background: rgba(0, 0, 0, 0.2);
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        font-size: 16px;
        color: var(--text-light, #fff);
    }

    .cart-total strong {
        color: var(--primary-purple, #9333EA);
        font-size: 20px;
    }

    .cart-actions {
        display: flex;
        gap: 10px;
    }

    .cart-actions .btn {
        flex: 1;
        padding: 8px 12px;
        font-size: 14px;
    }

    /* Empty cart */
    .empty-cart-message {
        padding: 40px 20px;
        color: var(--text-gray, rgba(255, 255, 255, 0.6));
    }

    /* Loading */
    .cart-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    /* Close button fix */
    .btn-close-white {
        filter: invert(1);
        opacity: 0.8;
    }

    .btn-close-white:hover {
        opacity: 1;
    }

    /* Remove button */
    .btn-link.text-danger {
        opacity: 0.7;
        transition: opacity 0.2s;
    }

    .btn-link.text-danger:hover {
        opacity: 1;
    }

    /* Mobile responsive */
    @media (max-width: 576px) {
        .cart-dropdown {
            right: -10px;
            width: calc(100vw - 20px);
            max-width: none;
        }
        
        .cart-dropdown-body {
            max-height: 50vh;
        }
        
        .cart-actions {
            flex-direction: column;
        }
        
        .cart-actions .btn {
            width: 100%;
        }
    }
    </style>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-close cart dropdown after item added
        Livewire.on('item-added-to-cart', () => {
            setTimeout(() => {
                @this.set('isOpen', false);
            }, 3000);
        });
    });
    </script>
    @endpush
</div>