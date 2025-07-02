@extends('layouts.app')

@section('title', $service->name . ' - Professional Event Services')
@section('description', $service->description ?? 'Book professional ' . $service->name . ' for your events in Sri Lanka.')

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
                <a href="{{ route('services.index', ['category' => strtolower(str_replace(' ', '-', $service->category))]) }}">
                    {{ $service->category }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
        </ol>
    </div>
</nav>

<!-- Service Detail -->
<section class="service-detail py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Service Gallery -->
            <div class="col-lg-6">
                <div class="service-gallery">
                    <div class="main-image mb-3">
                        <img src="{{ $service->image_url }}" 
                             alt="{{ $service->name }}" 
                             class="img-fluid rounded-3"
                             id="mainImage">
                        @if($service->badge)
                            <span class="service-badge-large {{ $service->badge_class ?? '' }}">
                                {{ $service->badge }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Service Info -->
            <div class="col-lg-6">
                <div class="service-detail-info">
                    <div class="service-category mb-2">{{ $service->category }}</div>
                    <h1 class="service-title mb-3">{{ $service->name }}</h1>
                    
                    @if($service->description)
                        <p class="service-description text-muted mb-4">{{ $service->description }}</p>
                    @endif
                    
                    <!-- Price Display -->
                    <div class="price-section mb-4">
                        <div class="price-label">Starting from</div>
                        <div class="price-value">
                            LKR {{ number_format($service->starting_price) }}
                            <span class="price-unit">/{{ $service->price_unit }}</span>
                        </div>
                    </div>
                    
                    <!-- Key Features -->
                    @php
                        $features = is_array($service->features) ? $service->features : json_decode($service->features, true);
                    @endphp
                    
                    @if($features && is_array($features) && count($features) > 0)
                        <div class="features-section mb-4">
                            <h3 class="section-title mb-3">What's Included</h3>
                            <ul class="feature-list">
                                @foreach($features as $feature)
                                    <li>
                                        <i class="fas fa-check-circle text-success"></i>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Service Options -->
                    <div class="service-options mb-4">
                        @if($service->experience_level)
                            <div class="option-item">
                                <span class="option-label">Experience Level:</span>
                                <span class="option-value">{{ $service->experience_level }}</span>
                            </div>
                        @endif
                        
                        @if($service->min_duration)
                            <div class="option-item">
                                <span class="option-label">Minimum Duration:</span>
                                <span class="option-value">{{ $service->min_duration }} hours</span>
                            </div>
                        @endif
                        
                        @if($service->equipment_included)
                            <div class="option-item">
                                <span class="option-label">Equipment:</span>
                                <span class="option-value text-success">Included</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Booking Form -->
                    <div class="booking-form">
                        <form id="serviceBookingForm">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Event Date</label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="event_date" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Duration (hours)</label>
                                    <input type="number" 
                                           class="form-control" 
                                           name="duration" 
                                           min="{{ $service->min_duration ?? 1 }}"
                                           max="{{ $service->max_duration ?? 24 }}"
                                           value="{{ $service->min_duration ?? 4 }}"
                                           required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-calendar-check me-2"></i>
                                Book This Service
                            </button>
                        </form>
                    </div>
                    
                    <!-- Additional Info -->
                    @if($service->additional_charges)
                        <div class="additional-info mt-4">
                            <p class="small text-muted">
                                <i class="fas fa-info-circle"></i>
                                Additional charges may apply for extended hours, special requests, or locations outside Colombo.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Additional Details Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                @php
                    $languages = is_array($service->languages) ? $service->languages : json_decode($service->languages, true);
                    $genres_specialties = is_array($service->genres_specialties) ? $service->genres_specialties : json_decode($service->genres_specialties, true);
                @endphp
                
                <ul class="nav nav-tabs" id="serviceDetailsTab" role="tablist">
                    @if($languages && is_array($languages) && count($languages) > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" 
                                    id="languages-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#languages" 
                                    type="button" 
                                    role="tab">
                                Languages
                            </button>
                        </li>
                    @endif
                    
                    @if($genres_specialties && is_array($genres_specialties) && count($genres_specialties) > 0)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ (!$languages || !is_array($languages) || count($languages) == 0) ? 'active' : '' }}" 
                                    id="specialties-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#specialties" 
                                    type="button" 
                                    role="tab">
                                Specialties
                            </button>
                        </li>
                    @endif
                    
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ (!$languages || !is_array($languages) || count($languages) == 0) && (!$genres_specialties || !is_array($genres_specialties) || count($genres_specialties) == 0) ? 'active' : '' }}" 
                                id="terms-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#terms" 
                                type="button" 
                                role="tab">
                            Terms & Conditions
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="serviceDetailsTabContent">
                    @if($languages && is_array($languages) && count($languages) > 0)
                        <div class="tab-pane fade show active" id="languages" role="tabpanel">
                            <div class="p-4">
                                <h4>Available Languages</h4>
                                <div class="language-tags mt-3">
                                    @foreach($languages as $language)
                                        <span class="badge bg-secondary me-2 mb-2">{{ $language }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($genres_specialties && is_array($genres_specialties) && count($genres_specialties) > 0)
                        <div class="tab-pane fade {{ (!$languages || !is_array($languages) || count($languages) == 0) ? 'show active' : '' }}" id="specialties" role="tabpanel">
                            <div class="p-4">
                                <h4>Genres & Specialties</h4>
                                <div class="specialty-tags mt-3">
                                    @foreach($genres_specialties as $specialty)
                                        <span class="badge bg-primary me-2 mb-2">{{ $specialty }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="tab-pane fade {{ (!$languages || !is_array($languages) || count($languages) == 0) && (!$genres_specialties || !is_array($genres_specialties) || count($genres_specialties) == 0) ? 'show active' : '' }}" id="terms" role="tabpanel">
                        <div class="p-4">
                            <h4>Terms & Conditions</h4>
                            <ul class="mt-3">
                                <li>Booking must be confirmed at least 48 hours in advance</li>
                                <li>50% advance payment required to confirm booking</li>
                                <li>Cancellation must be made 24 hours before the event</li>
                                <li>Additional charges apply for services outside Colombo</li>
                                <li>Client must provide necessary facilities at the venue</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Services -->
        @if($relatedServices->count() > 0)
            <div class="related-services mt-5">
                <h2 class="section-title text-center mb-4">Related Services</h2>
                <div class="row g-4">
                    @foreach($relatedServices as $related)
                        <div class="col-lg-3 col-md-6">
                            <x-service-card :service="$related" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
/* Service Detail Styles */
.service-detail {
    background-color: var(--bg-darker);
    min-height: 600px;
}

.service-gallery {
    position: relative;
}

.main-image {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
}

.main-image img {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: cover;
}

.service-badge-large {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.service-category {
    color: var(--primary-purple);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.service-title {
    font-size: 36px;
    font-weight: 800;
    color: var(--text-light);
}

.service-description {
    font-size: 16px;
    line-height: 1.8;
}

/* Price Section */
.price-section {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
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
    font-size: 18px;
    color: var(--text-gray);
}

/* Features Section */
.section-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-light);
}

.feature-list {
    list-style: none;
    padding: 0;
}

.feature-list li {
    padding: 10px 0;
    border-bottom: 1px solid var(--border-dark);
    color: var(--text-gray);
    display: flex;
    align-items: center;
    gap: 10px;
}

.feature-list li:last-child {
    border-bottom: none;
}

/* Service Options */
.service-options {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
}

.option-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-dark);
}

.option-item:last-child {
    border-bottom: none;
}

.option-label {
    font-size: 14px;
    color: var(--text-gray);
}

.option-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-light);
}

/* Form Styles */
.booking-form .form-control {
    background: var(--bg-card);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    padding: 12px 15px;
    font-size: 14px;
}

.booking-form .form-control:focus {
    background: var(--bg-card);
    border-color: var(--primary-purple);
    color: var(--text-light);
    box-shadow: 0 0 0 0.25rem rgba(147, 51, 234, 0.25);
}

.booking-form .form-label {
    color: var(--text-gray);
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
}

/* Tabs */
.nav-tabs {
    border-bottom: 1px solid var(--border-dark);
}

.nav-tabs .nav-link {
    background: transparent;
    border: none;
    color: var(--text-gray);
    padding: 15px 25px;
    font-weight: 600;
    transition: all 0.3s;
}

.nav-tabs .nav-link:hover {
    color: var(--primary-purple);
}

.nav-tabs .nav-link.active {
    color: var(--primary-purple);
    border-bottom: 3px solid var(--primary-purple);
}

.tab-content {
    background: var(--bg-card);
    border-radius: 0 0 15px 15px;
}

/* Language and Specialty Tags */
.language-tags .badge,
.specialty-tags .badge {
    font-size: 14px;
    padding: 8px 15px;
    font-weight: 500;
}

/* Related Services */
.related-services {
    padding-top: 40px;
    border-top: 1px solid var(--border-dark);
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('serviceBookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    const eventDate = formData.get('event_date');
    const duration = formData.get('duration');
    
    // Here you would typically add the service to cart or redirect to checkout
    // For now, we'll just show an alert
    alert(`Service booking request received!\nDate: ${eventDate}\nDuration: ${duration} hours\n\nPlease implement the booking functionality.`);
});
</script>
@endpush

@endsection