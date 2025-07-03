@props([
    'product',
    'showCategory' => true,
    'showQuickAdd' => true
])

<div class="product-card h-100">
    <div class="product-image">
        <img src="{{ $product->main_image_url ?? 'https://via.placeholder.com/400x300' }}" 
             alt="{{ $product->name }}"
             loading="lazy">
        
        @if($product->badges)
            <div class="product-badges">
                @foreach($product->badges as $badge)
                    <span class="badge-custom">{{ $badge }}</span>
                @endforeach
            </div>
        @endif
        
        <span class="availability-badge {{ $product->availability_class }}">
            @if($product->available_quantity > 0)
                {{ $product->available_quantity }} Available
            @else
                Out of Stock
            @endif
        </span>
    </div>
    
    <div class="product-info">
        @if($showCategory)
            <div class="product-category">{{ $product->category->name }}</div>
        @endif
        
        <a href="{{ route('product.show', [$product->category->slug, $product->slug]) }}" 
           class="product-title">
            {{ $product->name }}
        </a>
        
        @if($product->specifications)
            <ul class="product-specs">
                @foreach(array_slice($product->specifications, 0, 3) as $spec)
                    <li><i class="{{ $spec['icon'] ?? 'fas fa-check' }}"></i> {{ $spec['value'] }}</li>
                @endforeach
            </ul>
        @endif
        
        <div class="product-footer">
            <div class="product-price">
                LKR {{ number_format($product->base_price) }}
                <small>/{{ $product->price_unit ?? 'day' }}</small>
            </div>
            
            @if($showQuickAdd)
                <div class="product-actions">
                    <button class="btn-icon" 
                            title="Quick View"
                            data-bs-toggle="modal" 
                            data-bs-target="#quickViewModal"
                            data-product-id="{{ $product->id }}">
                        <i class="fas fa-eye"></i>
                    </button>
                    
                  @if($product->available_quantity > 0)
    <button class="btn-icon add-to-cart" 
            title="Add to Cart"
            data-product-id="{{ $product->id }}"
            onclick="addToCart({{ $product->id }}, this)">
        <i class="fas fa-shopping-cart"></i>
    </button>
@else
                        <button class="btn-icon" disabled title="Out of Stock">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.product-card {
    background: var(--bg-card);
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    transition: all 0.3s;
    border: 1px solid var(--border-dark);
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(147, 51, 234, 0.3);
    border-color: var(--primary-purple);
}

.product-image {
    height: 250px;
    overflow: hidden;
    position: relative;
    background: var(--bg-darker);
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.badge-custom {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.availability-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
}

.availability-badge.in-stock {
    background: rgba(34, 197, 94, 0.9);
}

.availability-badge.low-stock {
    background: rgba(245, 158, 11, 0.9);
}

.availability-badge.out-stock {
    background: rgba(239, 68, 68, 0.9);
}

.product-info {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-category {
    color: var(--primary-purple);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.product-title {
    color: var(--text-light);
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    text-decoration: none;
    transition: color 0.3s;
}

.product-title:hover {
    color: var(--secondary-purple);
}

.product-specs {
    list-style: none;
    padding: 0;
    margin-bottom: 15px;
    flex-grow: 1;
}

.product-specs li {
    color: var(--text-gray);
    font-size: 14px;
    margin-bottom: 5px;
}

.product-specs i {
    color: var(--primary-purple);
    margin-right: 8px;
    width: 16px;
}

.product-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid var(--border-dark);
}

.product-price {
    font-size: 24px;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.product-price small {
    font-size: 14px;
    color: var(--text-gray);
}

.product-actions {
    display: flex;
    gap: 10px;
}

.btn-icon {
    background-color: var(--bg-dark);
    border: 1px solid var(--border-dark);
    color: var(--text-gray);
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    cursor: pointer;
}

.btn-icon:hover {
    background-color: var(--primary-purple);
    border-color: var(--primary-purple);
    color: white;
    transform: translateY(-2px);
}

.btn-icon:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-icon:disabled:hover {
    background-color: var(--bg-dark);
    border-color: var(--border-dark);
    color: var(--text-gray);
    transform: none;
}
</style>

<script>
function addToCart(productId, button) {
    const originalHTML = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    // Get default rental period (1 day)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const eventDate = tomorrow.toISOString().split('T')[0];
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    const cartData = {
        type: 'product',
        product_id: productId,
        quantity: 1,
        rental_days: 1,
        event_date: eventDate
    };
    
    fetch('/cart/add', {
        method: 'POST',
        body: JSON.stringify(cartData),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update Livewire cart components
            if (window.Livewire) {
                Livewire.dispatch('itemAddedToCart');
            }
            
            // Show success animation
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.add('btn-success');
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.disabled = false;
            }, 1500);
        } else {
            alert(data.message || 'Failed to add to cart');
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        button.innerHTML = originalHTML;
        button.disabled = false;
    });
}
</script>