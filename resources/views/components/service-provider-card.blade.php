@props([
    'provider',
    'showBookButton' => true
])

<div class="provider-card h-100">
    <div class="provider-image">
        <img src="{{ $provider->profile_image_url }}" 
             alt="{{ $provider->display_name }}"
             loading="lazy">
        
        @if($provider->badge)
            <span class="provider-badge {{ $provider->badge_class ?? '' }}">
                {{ $provider->badge }}
            </span>
        @endif
        
        @if($provider->featured)
            <span class="featured-badge">
                <i class="fas fa-star"></i> Featured
            </span>
        @endif
    </div>
    
    <div class="provider-info">
        <div class="provider-header">
            <h4 class="provider-name">{{ $provider->display_name }}</h4>
            @if($provider->rating > 0)
                <div class="provider-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $provider->rating ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                    <span class="rating-value">{{ number_format($provider->rating, 1) }}</span>
                    <span class="review-count">({{ $provider->total_reviews }})</span>
                </div>
            @endif
        </div>
        
        <p class="provider-bio">{{ Str::limit($provider->bio, 100) }}</p>
        
        <div class="provider-details">
            @if($provider->experience_level)
                <div class="detail-item">
                    <i class="fas fa-award"></i>
                    {{ $provider->experience_level }}
                </div>
            @endif
            
            @if($provider->years_experience > 0)
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    {{ $provider->years_experience }} years
                </div>
            @endif
            
            @if($provider->languages && count($provider->languages) > 0)
                <div class="detail-item">
                    <i class="fas fa-language"></i>
                    {{ implode(', ', array_slice($provider->languages, 0, 2)) }}
                    @if(count($provider->languages) > 2)
                        +{{ count($provider->languages) - 2 }}
                    @endif
                </div>
            @endif
        </div>
        
        @if($provider->specialties && count($provider->specialties) > 0)
            <div class="provider-specialties">
                @foreach(array_slice($provider->specialties, 0, 3) as $specialty)
                    <span class="specialty-tag">{{ $specialty }}</span>
                @endforeach
                @if(count($provider->specialties) > 3)
                    <span class="specialty-tag">+{{ count($provider->specialties) - 3 }}</span>
                @endif
            </div>
        @endif
        
        <div class="provider-footer">
            <div class="provider-price">
                <span class="price-label">From</span>
                <span class="price-value">LKR {{ number_format($provider->base_price) }}</span>
                <span class="price-unit">/{{ $provider->price_unit }}</span>
            </div>
            
            @if($showBookButton)
                <a href="{{ route('services.provider', [$provider->category->slug, $provider->slug]) }}" 
                   class="btn btn-primary btn-sm">
                    View Profile
                </a>
            @endif
        </div>
    </div>
</div>

<style>
.provider-card {
    background: var(--bg-card);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s;
    border: 1px solid var(--border-dark);
    display: flex;
    flex-direction: column;
}

.provider-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(147, 51, 234, 0.2);
    border-color: var(--primary-purple);
}

.provider-image {
    height: 200px;
    position: relative;
    overflow: hidden;
    background-color: var(--bg-darker);
}

.provider-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.provider-card:hover .provider-image img {
    transform: scale(1.05);
}

.provider-badge {
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

.featured-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.provider-info {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.provider-header {
    margin-bottom: 15px;
}

.provider-name {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 5px;
    color: var(--text-light);
}

.provider-rating {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
}

.rating-value {
    font-weight: 600;
    color: var(--text-light);
    margin-left: 5px;
}

.review-count {
    color: var(--text-gray);
    font-size: 12px;
}

.provider-bio {
    color: var(--text-gray);
    font-size: 14px;
    margin-bottom: 15px;
    line-height: 1.6;
}

.provider-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--text-gray);
    font-size: 13px;
}

.detail-item i {
    color: var(--primary-purple);
    font-size: 12px;
}

.provider-specialties {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

.specialty-tag {
    background: rgba(147, 51, 234, 0.1);
    color: var(--primary-purple);
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid rgba(147, 51, 234, 0.2);
}

.provider-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid var(--border-dark);
}

.provider-price {
    display: flex;
    flex-direction: column;
}

.price-label {
    font-size: 12px;
    color: var(--text-gray);
}

.price-value {
    font-size: 20px;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.price-unit {
    font-size: 12px;
    color: var(--text-gray);
}
</style>