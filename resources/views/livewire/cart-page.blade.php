<div class="row">
    <div class="col-lg-8">
        <div class="cart-items-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-white mb-0">Cart Items ({{ $cart['count'] }})</h5>
                <button wire:click="clearCart" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash me-2"></i>Clear Cart
                </button>
            </div>
            
            @foreach($cart['items'] as $item)
                <div class="cart-item-card" wire:key="item-{{ $item['id'] }}">
                    <div class="row g-4">
                        <div class="col-md-2">
                            <div class="cart-item-image">
                                <img src="{{ $item['image'] ?? 'https://via.placeholder.com/150' }}" 
                                     alt="{{ $item['name'] }}">
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <h6 class="cart-item-title">{{ $item['name'] }}</h6>
                            @if($item['variation'])
                                <p class="text-muted small mb-1">Variation: {{ $item['variation'] }}</p>
                            @endif
                            <p class="text-muted small mb-2">Category: {{ $item['category'] }}</p>
                            @if($item['event_date'])
                                <p class="text-info small">
                                    <i class="fas fa-calendar me-1"></i>
                                    Event Date: {{ \Carbon\Carbon::parse($item['event_date'])->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                        
                        <div class="col-md-2">
                            <div class="quantity-controls">
                                <button wire:click="updateQuantity('{{ $item['id'] }}', {{ $item['quantity'] - 1 }})" 
                                        class="qty-btn-small">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       value="{{ $item['quantity'] }}" 
                                       class="qty-input-small"
                                       wire:change="updateQuantity('{{ $item['id'] }}', $event.target.value)"
                                       min="1">
                                <button wire:click="updateQuantity('{{ $item['id'] }}', {{ $item['quantity'] + 1 }})" 
                                        class="qty-btn-small">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="text-end">
                                <div class="cart-item-price">
                                    LKR {{ number_format($item['price'] * $item['quantity']) }}
                                </div>
                                <div class="text-muted small">
                                    LKR {{ number_format($item['price']) }} each
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-1">
                            <button wire:click="removeItem('{{ $item['id'] }}')" 
                                    class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="cart-summary">
            <h5 class="text-white mb-4">Order Summary</h5>
            
            <div class="summary-line">
                <span>Subtotal ({{ $cart['count'] }} items)</span>
                <span>LKR {{ number_format($cart['total']) }}</span>
            </div>
            
            @if(isset($cart['discount']) && $cart['discount'] > 0)
                <div class="summary-line text-success">
                    <span>Discount</span>
                    <span>-LKR {{ number_format($cart['discount']) }}</span>
                </div>
            @endif
            
            <div class="coupon-section">
                @if(!$couponApplied)
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               placeholder="Enter coupon code"
                               wire:model="couponCode">
                        <button class="btn btn-outline-primary" wire:click="applyCoupon">
                            Apply
                        </button>
                    </div>
                @else
                    <div class="coupon-applied">
                        <span class="text-success">
                            <i class="fas fa-tag me-2"></i>{{ $cart['coupon'] }}
                        </span>
                        <button class="btn btn-sm btn-link text-danger" wire:click="removeCoupon">
                            Remove
                        </button>
                    </div>
                @endif
                
                @if($couponMessage)
                    <small class="d-block mt-2 {{ $couponApplied ? 'text-success' : 'text-danger' }}">
                        {{ $couponMessage }}
                    </small>
                @endif
            </div>
            
            <div class="summary-total">
                <span>Total</span>
                <span class="total-amount">
                    LKR {{ number_format($cart['total'] - ($cart['discount'] ?? 0)) }}
                </span>
            </div>
            
            <button wire:click="proceedToCheckout" 
                    class="btn btn-primary w-100 btn-lg"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>
                    Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
                </span>
                <span wire:loading>
                    <i class="fas fa-spinner fa-spin"></i> Processing...
                </span>
            </button>
            
            <div class="text-center mt-3">
                <a href="{{ route('categories.index') }}" class="text-muted">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
            
            <div class="security-badges mt-4">
                <p class="text-muted small text-center mb-2">Secure Checkout</p>
                <div class="d-flex justify-content-center gap-3">
                    <i class="fab fa-cc-stripe fa-2x text-muted"></i>
                    <i class="fas fa-lock fa-2x text-muted"></i>
                    <i class="fas fa-shield-alt fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cart-items-section {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 30px;
    border: 1px solid var(--border-dark);
}

.cart-item-card {
    background: var(--bg-dark);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    border: 1px solid var(--border-dark);
    transition: all 0.3s;
}

.cart-item-card:hover {
    border-color: var(--primary-purple);
}

.cart-item-image {
    width: 100%;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item-title {
    color: var(--text-light);
    font-weight: 600;
    margin-bottom: 10px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn-small {
    width: 30px;
    height: 30px;
    border: 1px solid var(--border-dark);
    background: var(--bg-card);
    color: var(--text-light);
    border-radius: 5px;
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

.qty-input-small {
    width: 60px;
    text-align: center;
    background: var(--bg-card);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    padding: 5px;
    border-radius: 5px;
}

.cart-item-price {
    font-size: 18px;
    font-weight: 600;
    color: var(--primary-purple);
}

.cart-summary {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 30px;
    border: 1px solid var(--border-dark);
    position: sticky;
    top: 20px;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    color: var(--text-gray);
}

.coupon-section {
    padding: 20px 0;
    border-top: 1px solid var(--border-dark);
    border-bottom: 1px solid var(--border-dark);
    margin: 20px 0;
}

.coupon-applied {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    font-size: 20px;
    font-weight: 600;
}

.total-amount {
    font-size: 24px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.security-badges {
    padding-top: 20px;
    border-top: 1px solid var(--border-dark);
}

@media (max-width: 768px) {
    .cart-item-card .row {
        text-align: center;
    }
    
    .quantity-controls {
        justify-content: center;
        margin: 15px 0;
    }
    
    .cart-summary {
        margin-top: 30px;
    }
}
</style>