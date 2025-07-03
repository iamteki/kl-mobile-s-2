@extends('layouts.app')

@section('title', $package->name . ' - Event Package')
@section('description', $package->description)

@section('content')
    <!-- Page Header -->
    <section class="page-header" style="margin-top: 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">Packages</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $package->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ $package->name }}</h1>
                    @if($package->badge)
                        <span class="package-badge-large">{{ $package->badge }}</span>
                    @endif
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="package-price-header">
                        <span class="price-label">Package Price</span>
                        <div class="price-amount">LKR {{ number_format($package->price) }}</div>
                        <span class="price-unit">{{ $package->suitable_for ?? 'Per Event' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Package Details -->
    <section class="package-details py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Package Image -->
                    @if($package->image)
                        <div class="package-image mb-4">
                            <img src="{{ Storage::url($package->image) }}" 
                                 alt="{{ $package->name }}" 
                                 class="img-fluid rounded-20">
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="content-card mb-4">
                        <h2 class="content-title">Package Overview</h2>
                        <p class="package-description">{{ $package->description }}</p>
                        
                        @if($package->service_duration)
                            <div class="duration-info mt-3">
                                <i class="fas fa-clock me-2"></i>
                                <span>Service Duration: {{ $package->service_duration }} hours</span>
                            </div>
                        @endif
                    </div>

                    <!-- What's Included -->
                    <div class="content-card mb-4">
                        <h2 class="content-title">What's Included</h2>
                        @if($package->items && count($package->items) > 0)
                            <div class="items-grid">
                                @foreach($package->items as $item)
                                    <div class="item-card">
                                        <div class="item-icon">
                                            <i class="{{ $item['icon'] ?? 'fas fa-check' }}"></i>
                                        </div>
                                        <div class="item-details">
                                            <h5>{{ $item['name'] ?? 'Item' }}</h5>
                                            @if(isset($item['quantity']))
                                                <span class="item-quantity">Qty: {{ $item['quantity'] }}</span>
                                            @endif
                                            @if(isset($item['description']))
                                                <p class="item-description">{{ $item['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Contact us for detailed item list.</p>
                        @endif
                    </div>

                    <!-- Features -->
                    <div class="content-card mb-4">
                        <h2 class="content-title">Package Features</h2>
                        @if($package->features && count($package->features) > 0)
                            <div class="features-list">
                                @foreach($package->features as $feature)
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Additional Information -->
                    <div class="content-card">
                        <h2 class="content-title">Important Information</h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <i class="fas fa-truck"></i>
                                <div>
                                    <h6>Delivery & Setup</h6>
                                    <p>Free delivery and professional setup included</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <div>
                                    <h6>Technical Support</h6>
                                    <p>Professional crew available throughout event</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <h6>Insurance</h6>
                                    <p>All equipment fully insured for your peace of mind</p>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar-check"></i>
                                <div>
                                    <h6>Booking</h6>
                                    <p>Advance booking required, subject to availability</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Booking Card -->
                    <div class="booking-card sticky-top">
                        <h3 class="booking-title">Book This Package</h3>
                        
                        <div class="price-display mb-4">
                            <span class="currency">LKR</span>
                            <span class="amount">{{ number_format($package->price) }}</span>
                        </div>

                        <!-- Quick Info -->
                        <div class="quick-info mb-4">
                            @if($package->category)
                                <div class="info-row">
                                    <span class="info-label">Category:</span>
                                    <span class="info-value">{{ $package->category }}</span>
                                </div>
                            @endif
                            @if($package->suitable_for)
                                <div class="info-row">
                                    <span class="info-label">Suitable for:</span>
                                    <span class="info-value">{{ $package->suitable_for }}</span>
                                </div>
                            @endif
                            <div class="info-row">
                                <span class="info-label">Duration:</span>
                                <span class="info-value">{{ $package->service_duration ?? 8 }} hours</span>
                            </div>
                        </div>

                        <!-- Livewire Booking Component -->
                        @livewire('package-booking', ['package' => $package])
                    </div>

                    <!-- Trust Badges -->
                    <div class="trust-badges mt-4">
                        <div class="badge-item">
                            <i class="fas fa-award"></i>
                            <span>Premium Quality Equipment</span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-headset"></i>
                            <span>24/7 Customer Support</span>
                        </div>
                        <div class="badge-item">
                            <i class="fas fa-star"></i>
                            <span>5-Star Rated Service</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Packages -->
            @if($relatedPackages->count() > 0)
                <div class="related-packages mt-5">
                    <h2 class="section-title text-center mb-4">Other Packages You May Like</h2>
                    <div class="row g-4">
                        @foreach($relatedPackages as $relatedPackage)
                            <div class="col-lg-4">
                                <x-package-card :package="$relatedPackage" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
/* Page Adjustments for Fixed Navbar */
.page-header {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    padding: 100px 0 50px;
    border-bottom: 1px solid var(--border-dark);
    margin-top: 80px; /* Adjust based on your navbar height */
}

.page-title {
    font-size: 42px;
    font-weight: 800;
    color: var(--text-light);
    margin-bottom: 10px;
}

.package-badge-large {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
    color: white;
    padding: 8px 24px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    margin-top: 10px;
}

/* Price Header */
.package-price-header {
    text-align: right;
}

.price-label {
    display: block;
    color: var(--text-gray);
    font-size: 14px;
    margin-bottom: 5px;
}

.price-amount {
    font-size: 36px;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.price-unit {
    display: block;
    color: var(--text-gray);
    font-size: 14px;
    margin-top: 5px;
}

/* Package Image */
.package-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 20px;
}

/* Content Card */
.content-card {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 40px;
    border: 1px solid var(--border-dark);
}

.content-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-light);
    margin-bottom: 25px;
}

.package-description {
    font-size: 18px;
    line-height: 1.8;
    color: var(--text-gray);
}

.duration-info {
    display: inline-flex;
    align-items: center;
    background: rgba(147, 51, 234, 0.1);
    padding: 10px 20px;
    border-radius: 25px;
    color: var(--primary-purple);
    font-weight: 600;
}

/* Items Grid */
.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.item-card {
    background: var(--bg-darker);
    border-radius: 15px;
    padding: 20px;
    display: flex;
    gap: 15px;
    border: 1px solid var(--border-dark);
    transition: all 0.3s;
}

.item-card:hover {
    border-color: var(--primary-purple);
    transform: translateY(-2px);
}

.item-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.2) 0%, rgba(124, 58, 237, 0.2) 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.item-icon i {
    font-size: 20px;
    color: var(--primary-purple);
}

.item-details h5 {
    color: var(--text-light);
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
}

.item-quantity {
    display: inline-block;
    background: rgba(147, 51, 234, 0.2);
    color: var(--primary-purple);
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.item-description {
    color: var(--text-gray);
    font-size: 14px;
    margin-top: 8px;
    margin-bottom: 0;
}

/* Features List */
.features-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 15px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.feature-item i {
    color: var(--success-green);
    font-size: 20px;
}

.feature-item span {
    color: var(--text-light);
    font-size: 16px;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.info-item {
    display: flex;
    gap: 15px;
}

.info-item i {
    font-size: 24px;
    color: var(--primary-purple);
    width: 30px;
    text-align: center;
}

.info-item h6 {
    color: var(--text-light);
    font-weight: 600;
    margin-bottom: 5px;
}

.info-item p {
    color: var(--text-gray);
    font-size: 14px;
    margin: 0;
}

/* Package Details Section */
.package-details {
    min-height: 100vh; /* Ensure enough height for scrolling */
}

/* Booking Card */
.booking-card {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 30px;
    border: 1px solid var(--border-dark);
    position: sticky;
    top: 100px; /* Adjust based on navbar height + desired gap */
    z-index: 10; /* Below navbar */
    transition: box-shadow 0.3s ease;
}

/* Add shadow when stuck */
.booking-card.stuck {
    box-shadow: 0 10px 30px rgba(147, 51, 234, 0.2);
    border-color: var(--primary-purple);
}

.booking-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-light);
    margin-bottom: 20px;
    text-align: center;
}

.price-display {
    text-align: center;
    padding: 20px;
    background: var(--bg-darker);
    border-radius: 15px;
}

.price-display .currency {
    font-size: 20px;
    color: var(--text-gray);
    margin-right: 5px;
}

.price-display .amount {
    font-size: 36px;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Quick Info */
.quick-info {
    background: var(--bg-darker);
    border-radius: 15px;
    padding: 20px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-dark);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: var(--text-gray);
    font-size: 14px;
}

.info-value {
    color: var(--text-light);
    font-weight: 600;
    font-size: 14px;
}

/* Contact Info */
.contact-link {
    color: var(--primary-purple);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.contact-link:hover {
    color: var(--secondary-purple);
}

/* Trust Badges */
.trust-badges {
    background: var(--bg-darker);
    border-radius: 15px;
    padding: 20px;
}

.badge-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
}

.badge-item i {
    color: var(--primary-purple);
    font-size: 20px;
}

.badge-item span {
    color: var(--text-gray);
    font-size: 14px;
}

/* Related Packages */
.related-packages {
    padding-top: 40px;
    border-top: 1px solid var(--border-dark);
}

.section-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-light);
}

/* Package Booking Component Styles */
.booking-form-wrapper {
    background: var(--bg-darker);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
}

.form-title {
    color: var(--text-light);
    font-weight: 600;
    font-size: 20px;
}

.form-label {
    color: var(--text-light);
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-control,
.form-select {
    background-color: var(--bg-card);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    padding: 12px 15px;
    border-radius: 10px;
    transition: all 0.3s;
}

.form-control:focus,
.form-select:focus {
    background-color: var(--bg-card);
    border-color: var(--primary-purple);
    color: var(--text-light);
    box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.form-select option {
    background-color: var(--bg-card);
    color: var(--text-light);
}

.is-invalid {
    border-color: var(--danger-red);
}

.invalid-feedback {
    color: var(--danger-red);
    font-size: 13px;
    margin-top: 5px;
}

.quick-contact {
    padding-top: 20px;
    border-top: 1px solid var(--border-dark);
}

/* Responsive */
@media (max-width: 991px) {
    .page-title {
        font-size: 32px;
    }
    
    .booking-card {
        position: relative;
        top: 0;
        margin-top: 30px;
    }
    
    .items-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767px) {
    .content-card {
        padding: 25px;
    }
    
    .content-title {
        font-size: 24px;
    }
    
    .features-list {
        grid-template-columns: 1fr;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

@media (max-width: 576px) {
    .booking-form-wrapper {
        padding: 20px;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sticky card functionality
    const bookingCard = document.querySelector('.booking-card');
    if (bookingCard) {
        let lastScroll = 0;
        
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            const cardTop = bookingCard.getBoundingClientRect().top;
            
            // Add stuck class when card is stuck to top
            if (cardTop <= 100) {
                bookingCard.classList.add('stuck');
            } else {
                bookingCard.classList.remove('stuck');
            }
            
            lastScroll = currentScroll;
        });
    }
});
</script>
@endpush