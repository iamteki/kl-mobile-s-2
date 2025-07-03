@extends('layouts.app')

@section('title', $provider->display_name . ' - ' . $provider->category->name)
@section('description', $provider->bio)

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3" style="background-color: var(--bg-darker);">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('services.index') }}">Services</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('services.category', $provider->category->slug) }}">
                    {{ $provider->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $provider->display_name }}</li>
        </ol>
    </div>
</nav>

<!-- Provider Detail -->
<section class="provider-detail py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Left Column - Gallery & Info -->
            <div class="col-lg-8">
                <!-- Provider Header -->
                <div class="provider-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <img src="{{ $provider->profile_image_url }}" 
                                 alt="{{ $provider->display_name }}"
                                 class="provider-profile-img">
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h1 class="provider-name mb-2">{{ $provider->display_name }}</h1>
                                    @if($provider->stage_name && $provider->stage_name != $provider->name)
                                        <p class="real-name text-muted mb-2">{{ $provider->name }}</p>
                                    @endif
                                    <div class="provider-meta mb-3">
                                        @if($provider->badge)
                                            <span class="badge bg-primary me-2">{{ $provider->badge }}</span>
                                        @endif
                                        @if($provider->featured)
                                            <span class="badge bg-success me-2">
                                                <i class="fas fa-star"></i> Featured
                                            </span>
                                        @endif
                                        <span class="badge bg-secondary">
                                            {{ $provider->experience_level }}
                                        </span>
                                    </div>
                                    @if($provider->rating > 0)
                                        <div class="provider-rating mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $provider->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-2">
                                                {{ number_format($provider->rating, 1) }} 
                                                ({{ $provider->total_reviews }} {{ Str::plural('review', $provider->total_reviews) }})
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- About Section -->
                <div class="provider-section">
                    <h3 class="section-title">About</h3>
                    <p class="provider-bio">{{ $provider->bio }}</p>
                    
                    <div class="provider-stats mt-4">
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <i class="fas fa-briefcase"></i>
                                    <span class="stat-value">{{ $provider->years_experience }}</span>
                                    <span class="stat-label">Years Experience</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <i class="fas fa-calendar-check"></i>
                                    <span class="stat-value">{{ $provider->total_bookings }}</span>
                                    <span class="stat-label">Completed Events</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <i class="fas fa-language"></i>
                                    <span class="stat-value">{{ count($provider->languages ?? []) }}</span>
                                    <span class="stat-label">Languages</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <i class="fas fa-clock"></i>
                                    <span class="stat-value">{{ $provider->min_booking_hours }}+</span>
                                    <span class="stat-label">Min Hours</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Portfolio/Media Gallery -->
                @if($provider->mediaItems->count() > 0)
                    <div class="provider-section">
                        <h3 class="section-title">Portfolio</h3>
                        <div class="media-gallery">
                            <div class="row g-3">
                                @foreach($provider->mediaItems as $media)
                                    <div class="col-md-4">
                                        <div class="media-item" data-type="{{ $media->type }}">
                                            @if($media->type == 'photo')
                                                <img src="{{ $media->url }}" 
                                                     alt="{{ $media->title }}"
                                                     class="img-fluid"
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#mediaModal{{ $media->id }}">
                                            @elseif($media->type == 'video')
                                                <div class="video-thumbnail" 
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#mediaModal{{ $media->id }}">
                                                    <img src="{{ $media->thumbnail_url }}" 
                                                         alt="{{ $media->title }}"
                                                         class="img-fluid">
                                                    <div class="play-icon">
                                                        <i class="fas fa-play"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($media->title)
                                                <p class="media-title">{{ $media->title }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Media Modal -->
                                    <div class="modal fade" id="mediaModal{{ $media->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content bg-dark">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">{{ $media->title }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($media->type == 'photo')
                                                        <img src="{{ $media->url }}" alt="{{ $media->title }}" class="img-fluid">
                                                    @elseif($media->type == 'video')
                                                        <div class="ratio ratio-16x9">
                                                            <iframe src="{{ $media->embed_url }}" allowfullscreen></iframe>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Specialties -->
                @if($provider->specialties && count($provider->specialties) > 0)
                    <div class="provider-section">
                        <h3 class="section-title">Specialties</h3>
                        <div class="specialties-tags">
                            @foreach($provider->specialties as $specialty)
                                <span class="specialty-tag">{{ $specialty }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Reviews -->
                @if($provider->approvedReviews->count() > 0)
                    <div class="provider-section">
                        <h3 class="section-title">Reviews</h3>
                        @foreach($featuredReviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <h5 class="reviewer-name">{{ $review->customer->user->name }}</h5>
                                        <div class="review-meta">
                                            <span class="review-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </span>
                                            <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    @if($review->verified_booking)
                                        <span class="verified-badge">
                                            <i class="fas fa-check-circle"></i> Verified Booking
                                        </span>
                                    @endif
                                </div>
                                @if($review->title)
                                    <h6 class="review-title">{{ $review->title }}</h6>
                                @endif
                                <p class="review-comment">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                        
                        @if($provider->approvedReviews->count() > 3)
                            <button class="btn btn-outline-primary btn-sm mt-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#allReviewsModal">
                                View All Reviews ({{ $provider->approvedReviews->count() }})
                            </button>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- Right Column - Booking -->
            <div class="col-lg-4">
                <div class="booking-card">
                    <h3 class="booking-title">Book {{ $provider->display_name }}</h3>
                    
                    <!-- Pricing Tiers -->
                    @if($provider->pricingTiers->count() > 0)
                        <div class="pricing-tiers mb-4">
                            @foreach($provider->pricingTiers as $tier)
                                <div class="pricing-tier {{ $tier->is_popular ? 'popular' : '' }}">
                                    @if($tier->is_popular)
                                        <span class="popular-badge">Most Popular</span>
                                    @endif
                                    <h5 class="tier-name">{{ $tier->tier_name }}</h5>
                                    <div class="tier-price">
                                        LKR {{ number_format($tier->price) }}
                                    </div>
                                    <div class="tier-duration">{{ $tier->duration }}</div>
                                    @if($tier->included_features)
                                        <ul class="tier-features">
                                            @foreach($tier->included_features as $feature)
                                                <li>{{ $feature }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="base-pricing mb-4">
                            <div class="price-display">
                                <span class="price-label">Starting from</span>
                                <span class="price-value">LKR {{ number_format($provider->base_price) }}</span>
                                <span class="price-unit">/{{ $provider->price_unit }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Booking Form -->
                    <form id="providerBookingForm" class="booking-form">
                        @csrf
                        <input type="hidden" name="provider_id" value="{{ $provider->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   name="event_date" 
                                   id="event_date"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   required>
                        </div>
                        
                        @if($provider->pricingTiers->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Select Package</label>
                                <select class="form-select" name="pricing_tier_id" id="pricing_tier_id" required>
                                    <option value="">Choose a package</option>
                                    @foreach($provider->pricingTiers as $tier)
                                        <option value="{{ $tier->id }}" 
                                                data-price="{{ $tier->price }}"
                                                data-duration="{{ $tier->duration }}"
                                                {{ $tier->is_popular ? 'selected' : '' }}>
                                            {{ $tier->tier_name }} - LKR {{ number_format($tier->price) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label">Duration (hours)</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="duration" 
                                       id="duration"
                                       min="{{ $provider->min_booking_hours }}"
                                       max="{{ $provider->max_booking_hours ?? 24 }}"
                                       value="{{ $provider->min_booking_hours }}"
                                       required>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" 
                                   class="form-control" 
                                   name="start_time" 
                                   id="start_time"
                                   required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Add to Cart
                        </button>
                        
                        <button type="button" class="btn btn-outline-primary w-100" id="checkAvailabilityBtn">
                            <i class="fas fa-calendar-check me-2"></i>
                            Check Availability
                        </button>
                    </form>
                    
                    <!-- Availability Calendar Preview -->
                    <div class="availability-preview mt-4">
                        <h5 class="mb-3">Availability</h5>
                        <div class="calendar-mini">
                            <div class="calendar-grid">
                                @foreach(array_slice($availabilityDates, 0, 14) as $date)
                                    <div class="calendar-day {{ $date['available'] ? 'available' : 'unavailable' }}" 
                                         title="{{ date('M j', strtotime($date['date'])) }}">
                                        {{ $date['display'] }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="calendar-legend mt-2">
                                <span class="legend-item">
                                    <span class="legend-box available"></span> Available
                                </span>
                                <span class="legend-item">
                                    <span class="legend-box unavailable"></span> Unavailable
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="contact-info mt-4">
                        <h5 class="mb-3">Quick Info</h5>
                        <ul class="info-list">
                            @if($provider->languages)
                                <li>
                                    <i class="fas fa-language"></i>
                                    Languages: {{ implode(', ', $provider->languages) }}
                                </li>
                            @endif
                            @if($provider->equipment_provided)
                                <li>
                                    <i class="fas fa-check-circle text-success"></i>
                                    Equipment Provided
                                </li>
                            @endif
                            <li>
                                <i class="fas fa-clock"></i>
                                Min Booking: {{ $provider->min_booking_hours }} hours
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Providers -->
        @if($relatedProviders->count() > 0)
            <div class="related-providers mt-5">
                <h2 class="section-title text-center mb-4">Similar Service Providers</h2>
                <div class="row g-4">
                    @foreach($relatedProviders as $related)
                        <div class="col-lg-3 col-md-6">
                            <x-service-provider-card :provider="$related" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

<!-- All Reviews Modal -->
<div class="modal fade" id="allReviewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Reviews for {{ $provider->display_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="reviews-list">
                    @foreach($provider->approvedReviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <h6 class="reviewer-name">{{ $review->customer->user->name }}</h6>
                                    <div class="review-meta">
                                        <span class="review-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </span>
                                        <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($review->title)
                                <h6 class="review-title">{{ $review->title }}</h6>
                            @endif
                            <p class="review-comment">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Provider Detail Styles */
.provider-detail {
    background-color: var(--bg-darker);
    min-height: 600px;
}

/* Container max width */
.container {
    max-width: 1200px;
}

/* Profile Image */
.provider-profile-img {
    width: 100%;
    max-width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 20px;
    border: 3px solid var(--border-dark);
}

.provider-name {
    font-size: 32px;
    font-weight: 800;
    color: var(--text-light);
    word-wrap: break-word;
}

.real-name {
    font-size: 16px;
}

/* Prevent header overflow */
.provider-header {
    overflow: hidden;
}

.provider-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.provider-section {
    background: var(--bg-card);
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 20px;
    border: 1px solid var(--border-dark);
    overflow: hidden;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--text-light);
}

/* Stats Box */
.stat-box {
    background: var(--bg-darker);
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    border: 1px solid var(--border-dark);
}

.stat-box i {
    font-size: 24px;
    color: var(--primary-purple);
    margin-bottom: 10px;
    display: block;
}

.stat-value {
    display: block;
    font-size: 24px;
    font-weight: 700;
    color: var(--text-light);
}

.stat-label {
    display: block;
    font-size: 12px;
    color: var(--text-gray);
    text-transform: uppercase;
}

/* Media Gallery */
.media-gallery {
    margin-top: 20px;
}

.media-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    cursor: pointer;
    margin-bottom: 15px;
}

.media-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s;
}

.media-item:hover img {
    transform: scale(1.05);
}

.video-thumbnail {
    position: relative;
}

.play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: rgba(0, 0, 0, 0.8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.media-title {
    margin-top: 10px;
    font-size: 14px;
    color: var(--text-gray);
}

/* Specialties */
.specialties-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.specialty-tag {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
    color: var(--primary-purple);
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid rgba(147, 51, 234, 0.2);
}

/* Reviews */
.review-item {
    background: var(--bg-darker);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
    border: 1px solid var(--border-dark);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
}

.reviewer-name {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
}

.review-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 14px;
    color: var(--text-gray);
}

.review-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--text-light);
}

.review-comment {
    color: var(--text-gray);
    line-height: 1.6;
}

.verified-badge {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success-green);
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

/* Booking Card - NO STICKY, NO SCROLLBAR */
.booking-card {
    background: var(--bg-card);
    padding: 30px;
    border-radius: 20px;
    border: 1px solid var(--border-dark);
}

.booking-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 25px;
    color: var(--text-light);
}

/* Pricing Tiers */
.pricing-tier {
    background: var(--bg-darker);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 15px;
    border: 1px solid var(--border-dark);
    position: relative;
}

.pricing-tier.popular {
    border-color: var(--primary-purple);
}

.popular-badge {
    position: absolute;
    top: -10px;
    right: 20px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.tier-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}

.tier-price {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-purple);
}

.tier-duration {
    font-size: 14px;
    color: var(--text-gray);
    margin-bottom: 15px;
}

.tier-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tier-features li {
    padding: 5px 0;
    color: var(--text-gray);
    font-size: 14px;
    padding-left: 20px;
    position: relative;
}

.tier-features li::before {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    color: var(--success-green);
    position: absolute;
    left: 0;
}

/* Base Pricing */
.base-pricing {
    background: var(--bg-darker);
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    border: 1px solid var(--border-dark);
}

.price-display {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.price-label {
    font-size: 14px;
    color: var(--text-gray);
    margin-bottom: 5px;
}

.price-value {
    font-size: 32px;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.price-unit {
    font-size: 16px;
    color: var(--text-gray);
}

/* Calendar Mini */
.calendar-mini {
    background: var(--bg-darker);
    padding: 15px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}

.calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}

.calendar-day.available {
    background: rgba(34, 197, 94, 0.2);
    color: var(--success-green);
}

.calendar-day.unavailable {
    background: rgba(239, 68, 68, 0.1);
    color: var(--text-gray);
}

.calendar-legend {
    display: flex;
    justify-content: center;
    gap: 20px;
    font-size: 12px;
    color: var(--text-gray);
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.legend-box {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

.legend-box.available {
    background: rgba(34, 197, 94, 0.5);
}

.legend-box.unavailable {
    background: rgba(239, 68, 68, 0.3);
}

/* Contact Info */
.info-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-list li {
    padding: 8px 0;
    color: var(--text-gray);
    font-size: 14px;
}

.info-list i {
    width: 20px;
    margin-right: 10px;
    color: var(--primary-purple);
}

/* Related Providers */
.related-providers {
    padding-top: 40px;
    border-top: 1px solid var(--border-dark);
}

/* Modal Styles */
.modal-content {
    background: var(--bg-card) !important;
    border: 1px solid var(--border-dark);
}

.modal-header {
    border-bottom: 1px solid var(--border-dark);
}

.modal-title {
    color: var(--text-light);
}

/* Responsive */
@media (max-width: 1200px) {
    .container {
        max-width: 100%;
        padding: 0 15px;
    }
}

@media (max-width: 991px) {
    .provider-profile-img {
        margin-bottom: 20px;
    }
    
    .booking-card {
        margin-top: 30px;
    }
}

@media (max-width: 768px) {
    .provider-name {
        font-size: 24px;
    }
    
    .stat-box {
        padding: 15px;
    }
    
    .stat-value {
        font-size: 20px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Handle Add to Cart
document.getElementById('providerBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding to Cart...';
    
    // Prepare cart data
    const cartData = {
        type: 'service_provider',
        provider_id: formData.get('provider_id'),
        event_date: formData.get('event_date'),
        start_time: formData.get('start_time'),
        quantity: 1
    };
    
    // Add pricing tier or duration
    if (formData.get('pricing_tier_id')) {
        cartData.pricing_tier_id = formData.get('pricing_tier_id');
        const selectedOption = document.querySelector('#pricing_tier_id option:checked');
        cartData.price = selectedOption.dataset.price;
        cartData.duration = selectedOption.dataset.duration;
    } else {
        cartData.duration = formData.get('duration');
        cartData.price = {{ $provider->base_price }} * (cartData.duration / {{ $provider->min_booking_hours }});
    }
    
    // Add to cart via AJAX
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        body: JSON.stringify(cartData),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('success', 'Added to cart successfully!');
            
            // Update cart count in header
            updateCartCount();
            
            // Optionally redirect to cart
            setTimeout(() => {
                if (confirm('Service added to cart. Would you like to view your cart?')) {
                    window.location.href = '{{ route("cart.index") }}';
                }
            }, 500);
        } else {
            showNotification('error', data.message || 'Failed to add to cart');
        }
    })
    .catch(error => {
        showNotification('error', 'An error occurred. Please try again.');
        console.error('Error:', error);
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
});

// Handle Check Availability
document.getElementById('checkAvailabilityBtn').addEventListener('click', function() {
    const eventDate = document.getElementById('event_date').value;
    const providerId = document.querySelector('input[name="provider_id"]').value;
    
    if (!eventDate) {
        alert('Please select an event date first.');
        return;
    }
    
    const button = this;
    const originalText = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Checking...';
    
    // Get duration
    let duration;
    if (document.getElementById('pricing_tier_id')) {
        const selectedOption = document.querySelector('#pricing_tier_id option:checked');
        duration = selectedOption ? parseInt(selectedOption.dataset.duration) : {{ $provider->min_booking_hours }};
    } else {
        duration = document.getElementById('duration').value;
    }
    
    // Check availability
    fetch('{{ route("services.provider.check-availability", $provider->id) }}', {
        method: 'POST',
        body: JSON.stringify({
            date: eventDate,
            duration: duration
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.available) {
            showNotification('success', data.message);
        } else {
            showNotification('warning', data.message);
        }
    })
    .catch(error => {
        showNotification('error', 'Failed to check availability');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
});

// Notification helper
function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} notification-popup`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
    `;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Update cart count
function updateCartCount() {
    // This would typically fetch the cart count from the server
    // For now, just increment the visible count
    const cartBadge = document.querySelector('.cart-count');
    if (cartBadge) {
        const currentCount = parseInt(cartBadge.textContent) || 0;
        cartBadge.textContent = currentCount + 1;
    }
}

// Add notification styles
const style = document.createElement('style');
style.textContent = `
    .notification-popup {
        position: fixed;
        top: 100px;
        right: 20px;
        min-width: 300px;
        z-index: 9999;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    }
    
    .notification-popup.show {
        opacity: 1;
        transform: translateX(0);
    }
    
    .alert-success {
        background: rgba(34, 197, 94, 0.2);
        border: 1px solid var(--success-green);
        color: var(--success-green);
    }
    
    .alert-error {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid #ef4444;
        color: #ef4444;
    }
    
    .alert-warning {
        background: rgba(245, 158, 11, 0.2);
        border: 1px solid var(--warning-yellow);
        color: var(--warning-yellow);
    }
`;
document.head.appendChild(style);
</script>
@endpush

@endsection