<div class="cart-dropdown-container" x-data="{ open: @entangle('isOpen') }">
    <button class="btn btn-outline-primary position-relative" @click="open = !open">
        <i class="fas fa-shopping-cart"></i> 
        <span class="d-none d-md-inline">Cart</span>
        @if($cartCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $cartCount }}
                <span class="visually-hidden">items in cart</span>
            </span>
        @endif
    </button>
    
    <!-- Dropdown -->
    <div class="cart-dropdown" 
         x-show="open" 
         x-transition
         @click.away="open = false"
         style="display: none;">
        
        <div class="cart-dropdown-header">
            <h6 class="mb-0">Shopping Cart</h6>
            <button type="button" class="btn-close btn-close-white" @click="open = false"></button>
        </div>
        
        <div class="cart-dropdown-body">
            @if($cartCount > 0)
                @foreach($cartItems as $item)
                    <div class="cart-item" wire:key="cart-item-{{ $item['id'] }}">
                        <div class="cart-item-image">
                            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/60' }}" 
                                 alt="{{ $item['name'] }}">
                        </div>
                        
                        <div class="cart-item-details">
                            <h6 class="cart-item-name">{{ $item['name'] }}</h6>
                            <p class="cart-item-variation">
                                {{ $item['variation'] ?? 'Standard' }}
                            </p>
                            
                            <div class="cart-item-controls">
                                <div class="quantity-controls">
                                    <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})" 
                                            class="qty-btn-small">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="quantity">{{ $item['quantity'] }}</span>
                                    <button wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})" 
                                            class="qty-btn-small">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                
                                <div class="cart-item-price">
                                    LKR {{ number_format($item['price'] * $item['quantity']) }}
                                </div>
                            </div>
                        </div>
                        
                        <button wire:click="removeItem({{ $item['id'] }})" 
                                class="remove-item">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
                
                <div class="cart-dropdown-footer">
                    <div class="cart-total">
                        <span>Total:</span>
                        <strong>LKR {{ number_format($cartTotal) }}</strong>
                    </div>
                    
                    <div class="cart-actions">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm">
                            View Cart
                        </a>
                        <a href="{{ route('checkout.event-details') }}" class="btn btn-primary btn-sm">
                            Checkout
                        </a>
                    </div>
                </div>
            @else
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Your cart is empty</p>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary btn-sm">
                        Browse Equipment
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
    .cart-dropdown-container {
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
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        margin-top: 10px;
        z-index: 1050;
    }

    .cart-dropdown-header {
        padding: 20px;
        border-bottom: 1px solid var(--border-dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-dropdown-body {
        max-height: 400px;
        overflow-y: auto;
        padding: 20px;
    }

    .cart-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-dark);
        position: relative;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-details {
        flex: 1;
    }

    .cart-item-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--text-light);
    }

    .cart-item-variation {
        font-size: 12px;
        color: var(--text-gray);
        margin-bottom: 10px;
    }

    .cart-item-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .qty-btn-small {
        width: 24px;
        height: 24px;
        border: 1px solid var(--border-dark);
        background: var(--bg-dark);
        color: var(--text-light);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .qty-btn-small:hover {
        border-color: var(--primary-purple);
        color: var(--primary-purple);
    }

    .quantity {
        font-weight: 600;
        min-width: 30px;
        text-align: center;
    }

    .cart-item-price {
        font-weight: 600;
        color: var(--primary-purple);
    }

    .remove-item {
        position: absolute;
        top: 10px;
        right: 0;
        background: none;
        border: none;
        color: var(--text-gray);
        cursor: pointer;
        transition: color 0.3s;
    }

    .remove-item:hover {
        color: var(--danger-red);
    }

    .cart-dropdown-footer {
        padding: 20px;
        border-top: 1px solid var(--border-dark);
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .cart-actions {
        display: flex;
        gap: 10px;
    }

    .cart-actions .btn {
        flex: 1;
    }

    .empty-cart {
        text-align: center;
        padding: 40px 20px;
    }

    @media (max-width: 480px) {
        .cart-dropdown {
            width: 320px;
        }
    }
    </style>

    @push('scripts')
    <script>
        window.addEventListener('cart-dropdown-opened', event => {
            setTimeout(() => {
                @this.set('isOpen', false);
            }, 3000);
        });
    </script>
    @endpush
</div>