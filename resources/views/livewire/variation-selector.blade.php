<div class="variations-section">
    @if(count($variations) > 0)
        <div class="variation-group">
            <div class="variation-label">Select Variation:</div>
            <div class="variation-options">
                @foreach($variations as $variation)
                    <div class="variation-option {{ !$variation['available'] ? 'unavailable' : '' }} {{ $selectedVariation == $variation['id'] ? 'selected' : '' }}"
                         wire:click="{{ $variation['available'] ? 'selectVariation(' . $variation['id'] . ')' : '' }}">
                        <div>{{ $variation['name'] }}</div>
                        <div class="variation-price-diff">{{ $variation['price_formatted'] }}</div>
                        @if(!$variation['available'])
                            <div class="text-danger small">Out of Stock</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    @if($product->addons && count($product->addons) > 0)
        <div class="variation-group">
            <div class="variation-label">Additional Options:</div>
            <div class="variation-options">
                <div class="variation-option">
                    <div>Basic Setup</div>
                    <div class="variation-price-diff">Included</div>
                </div>
                <div class="variation-option">
                    <div>+ Wireless Mics (2)</div>
                    <div class="variation-price-diff">+LKR 5,000/day</div>
                </div>
                <div class="variation-option">
                    <div>+ Technician</div>
                    <div class="variation-price-diff">+LKR 8,000/day</div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.variations-section {
    margin-bottom: 30px;
}

.variation-group {
    margin-bottom: 25px;
}

.variation-label {
    font-weight: 600;
    margin-bottom: 15px;
    color: var(--text-light);
}

.variation-options {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.variation-option {
    padding: 10px 20px;
    background: var(--bg-card);
    border: 2px solid var(--border-dark);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.variation-option:hover {
    border-color: var(--primary-purple);
}

.variation-option.selected {
    border-color: var(--primary-purple);
    background: rgba(147, 51, 234, 0.1);
}

.variation-option.unavailable {
    opacity: 0.5;
    cursor: not-allowed;
}

.variation-price-diff {
    font-size: 12px;
    color: var(--text-gray);
    margin-top: 5px;
}
</style>