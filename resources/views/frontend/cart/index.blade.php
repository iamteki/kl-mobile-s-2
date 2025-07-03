@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<section class="cart-section py-5">
    <div class="container">
        <h1 class="mb-4">Shopping Cart</h1>
        
        @if($cartItems && count($cartItems) > 0)
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-items">
                        @foreach($cartItems as $itemId => $item)
                            <div class="cart-item" data-item-id="{{ $itemId }}">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100' }}" 
                                             alt="{{ $item['name'] }}" 
                                             class="cart-item-image">
                                    </div>
                                    <div class="col-md-5">
                                        <h5 class="cart-item-name">{{ $item['name'] }}</h5>
                                        
                                        @if($item['type'] === 'product')
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-calendar"></i> 
                                                {{ date('M d, Y', strtotime($item['event_date'])) }}
                                                <span class="ms-2">
                                                    <i class="fas fa-clock"></i> 
                                                    {{ $item['rental_days'] }} {{ Str::plural('day', $item['rental_days']) }}
                                                </span>
                                            </p>
                                        @elseif($item['type'] === 'service_provider')
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-user"></i> {{ $item['category'] ?? 'Service' }}
                                            </p>
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-calendar"></i> 
                                                {{ date('M d, Y', strtotime($item['event_date'])) }}
                                                at {{ date('h:i A', strtotime($item['start_time'])) }}
                                            </p>
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-clock"></i> 
                                                Duration: {{ $item['duration'] }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        @if($item['type'] === 'product')
                                            <input type="number" 
                                                   class="form-control quantity-input" 
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   data-item-id="{{ $itemId }}">
                                        @else
                                            <span class="text-muted">Qty: 1</span>
                                        @endif
                                    </div>
                                    <div class="col-md-2">
                                        <div class="cart-item-price">
                                            LKR {{ number_format($item['price'] * $item['quantity'] * ($item['rental_days'] ?? 1)) }}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-sm btn-outline-danger remove-item" 
                                                data-item-id="{{ $itemId }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h4 class="summary-title">Order Summary</h4>
                        
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="cart-subtotal">LKR {{ number_format($subtotal) }}</span>
                        </div>
                        
                        @if($tax > 0)
                            <div class="summary-row">
                                <span>Tax</span>
                                <span id="cart-tax">LKR {{ number_format($tax) }}</span>
                            </div>
                        @endif
                        
                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="cart-total">LKR {{ number_format($total) }}</span>
                        </div>
                        
                        <a href="{{ route('checkout.event-details') }}" class="btn btn-primary btn-lg w-100 mt-4">
                            Proceed to Checkout
                        </a>
                        
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart text-center py-5">
                <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted mb-4">Start adding items to your cart!</p>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
.cart-section {
    background-color: var(--bg-darker);
    min-height: 500px;
}

.cart-item {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
    border: 1px solid var(--border-dark);
}

.cart-item-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 10px;
}

.cart-item-name {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 10px;
}

.cart-item-price {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-purple);
}

.quantity-input {
    background: var(--bg-darker);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    text-align: center;
}

.cart-summary {
    background: var(--bg-card);
    padding: 30px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
    position: sticky;
    top: 100px;
}

.summary-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--text-light);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    color: var(--text-gray);
}

.summary-row.total {
    border-top: 1px solid var(--border-dark);
    margin-top: 10px;
    padding-top: 20px;
    font-size: 18px;
    font-weight: 700;
    color: var(--text-light);
}

.empty-cart {
    background: var(--bg-card);
    padding: 60px;
    border-radius: 20px;
    border: 1px solid var(--border-dark);
}
</style>
@endpush

@push('scripts')
<script>
// Update quantity
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        const itemId = this.dataset.itemId;
        const quantity = this.value;
        
        updateCartItem(itemId, quantity);
    });
});

// Remove item
document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', function() {
        const itemId = this.dataset.itemId;
        
        if (confirm('Remove this item from cart?')) {
            removeCartItem(itemId);
        }
    });
});

function updateCartItem(itemId, quantity) {
    fetch(`/cart/update/${itemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartTotals(data);
            if (quantity == 0) {
                document.querySelector(`[data-item-id="${itemId}"]`).remove();
            }
        }
    });
}

function removeCartItem(itemId) {
    fetch(`/cart/remove/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-item-id="${itemId}"]`).remove();
            updateCartTotals(data);
            
            if (data.item_count === 0) {
                location.reload();
            }
        }
    });
}

function updateCartTotals(data) {
    document.getElementById('cart-subtotal').textContent = 'LKR ' + new Intl.NumberFormat().format(data.subtotal);
    if (document.getElementById('cart-tax')) {
        document.getElementById('cart-tax').textContent = 'LKR ' + new Intl.NumberFormat().format(data.tax);
    }
    document.getElementById('cart-total').textContent = 'LKR ' + new Intl.NumberFormat().format(data.total);
}
</script>
@endpush

@endsection