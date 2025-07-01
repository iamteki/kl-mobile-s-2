@props([
    'package',
    'featured' => false
])

<div class="package-card {{ $featured ? 'featured' : '' }} h-100">
    @if($package->badge)
        <span class="package-badge">{{ $package->badge }}</span>
    @endif
    
    <h4>{{ $package->name }}</h4>
    <div class="package-price">LKR {{ number_format($package->price) }}</div>
    <p class="text-muted">{{ $package->description }}</p>
    
    <ul class="package-features">
        @foreach($package->features as $feature)
            <li>
                <i class="fas fa-check"></i>
                {{ $feature }}
            </li>
        @endforeach
    </ul>
    
    <button class="btn {{ $featured ? 'btn-primary' : 'btn-outline-primary' }} w-100"
            onclick="window.location.href='{{ route('package.show', $package->slug) }}'">
        Select Package
    </button>
</div>

<style>
.package-card {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 40px 30px;
    text-align: center;
    height: 100%;
    position: relative;
    transition: all 0.3s;
    border: 1px solid var(--border-dark);
    display: flex;
    flex-direction: column;
}

.package-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(147, 51, 234, 0.3);
    border-color: var(--primary-purple);
}

.package-card.featured {
    border: 2px solid var(--primary-purple);
    transform: scale(1.05);
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
}

.package-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
    color: white;
    padding: 5px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

.package-card h4 {
    color: var(--text-light);
    font-weight: 700;
    margin-bottom: 20px;
}

.package-price {
    font-size: 36px;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 20px 0;
}

.package-features {
    list-style: none;
    padding: 0;
    margin: 30px 0;
    text-align: left;
    flex-grow: 1;
}

.package-features li {
    padding: 10px 0;
    border-bottom: 1px solid var(--border-dark);
    color: var(--text-gray);
}

.package-features li:last-child {
    border-bottom: none;
}

.package-features i {
    color: var(--secondary-purple);
    margin-right: 10px;
}

@media (max-width: 991px) {
    .package-card.featured {
        transform: scale(1);
    }
}
</style>