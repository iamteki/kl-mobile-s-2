<span class="price-display">
    <span class="currency">{{ $currency }}</span>
    <span class="amount">{{ number_format($amount) }}</span>
    @if($period)
        <small class="period">/{{ $period }}</small>
    @endif
</span>

<style>
.price-display {
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.price-display .period {
    font-size: 0.7em;
    color: var(--text-gray);
}
</style>