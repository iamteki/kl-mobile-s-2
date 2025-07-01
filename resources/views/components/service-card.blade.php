@props([
    'service',
    'showBookButton' => true
])

<div class="service-card h-100">
    <div class="service-image">
        <img src="{{ $service->image_url ?? 'https://via.placeholder.com/600x400' }}" 
             alt="{{ $service->name }}"
             loading="lazy">
        
        @if($service->badge)
            <span class="service-badge {{ $service->badge_class ?? '' }}">
                {{ $service->badge }}
            </span>
        @endif
    </div>
    
    <div class="service-info">
        <div class="service-category">{{ $service->category }}</div>
        <h4 class="service-title">{{ $service->name }}</h4>
        
        @if($service->features)
            <ul class="service-features">
                @foreach($service->features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        @endif
        
        <div class="service-pricing">
            <div class="service-price">
                From LKR {{ number_format($service->starting_price) }}
                <small>/{{ $service->price_unit ?? 'event' }}</small>
            </div>
            
            @if($showBookButton)
                <button class="btn btn-primary btn-sm" 
                        onclick="window.location.href='{{ route('service.show', $service->slug) }}'">
                    Book Now
                </button>
            @endif
        </div>
    </div>
</div>

<style>
.service-card {
    background: var(--bg-card);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s;
    height: 100%;
    border: 1px solid var(--border-dark);
    position: relative;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(147, 51, 234, 0.3);
    border-color: var(--primary-purple);
}

.service-image {
    height: 250px;
    position: relative;
    overflow: hidden;
    background-color: var(--bg-darker);
}

.service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.service-card:hover .service-image img {
    transform: scale(1.1);
}

.service-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.badge-popular {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
}

.badge-premium {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    color: #000;
}

.service-info {
    padding: 25px;
}

.service-category {
    color: var(--primary-purple);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    font-weight: 600;
}

.service-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--text-light);
}

.service-features {
    list-style: none;
    padding: 0;
    margin-bottom: 20px;
}

.service-features li {
    color: var(--text-gray);
    font-size: 14px;
    margin-bottom: 8px;
    padding-left: 20px;
    position: relative;
}

.service-features li::before {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: var(--secondary-purple);
    position: absolute;
    left: 0;
}

.service-pricing {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--border-dark);
}

.service-price {
    font-size: 24px;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.service-price small {
    font-size: 14px;
    color: var(--text-gray);
}
</style>