@extends('layouts.app')

@section('title', 'Professional Event Services')
@section('description', 'Browse our professional service providers including DJs, photographers, videographers, and event staff.')

@section('content')
<!-- Hero Section -->
<section class="services-hero position-relative">
    <div class="hero-bg-overlay"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-3 fw-bold text-white mb-4">
                    Professional Event Services
                </h1>
                <p class="lead text-white-50 mb-5">
                    Find the perfect professionals for your event from our vetted network of service providers
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Service Categories -->
<section class="service-categories py-5">
    <div class="container">
        <!-- Category Tabs -->
        <div class="category-tabs mb-5">
            <ul class="nav nav-pills justify-content-center" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" 
                            data-bs-toggle="tab" 
                            data-bs-target="#all-services" 
                            type="button">
                        All Services
                    </button>
                </li>
                @foreach($parentCategories as $key => $name)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                data-bs-toggle="tab" 
                                data-bs-target="#{{ $key }}" 
                                type="button">
                            {{ $name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <!-- Tab Content -->
        <div class="tab-content">
            <!-- All Services -->
            <div class="tab-pane fade show active" id="all-services" role="tabpanel">
                <div class="row g-4">
                    @foreach($categories as $category)
                        <div class="col-lg-4 col-md-6">
                            @include('frontend.services.partials.category-card', ['category' => $category])
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Parent Category Tabs -->
            @foreach($parentCategories as $key => $name)
                <div class="tab-pane fade" id="{{ $key }}" role="tabpanel">
                    <div class="row g-4">
                        @if(isset($groupedCategories[$key]))
                            @foreach($groupedCategories[$key] as $category)
                                <div class="col-lg-4 col-md-6">
                                    @include('frontend.services.partials.category-card', ['category' => $category])
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                    <h3 class="text-muted">No services in this category yet</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Our Services -->
<section class="why-choose-services py-5 bg-dark">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose Our Service Providers</h2>
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="feature-box">
                    <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                    <h5>Vetted Professionals</h5>
                    <p class="text-muted">All providers are verified and experienced</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-box">
                    <i class="fas fa-star fa-3x text-warning mb-3"></i>
                    <h5>Customer Reviews</h5>
                    <p class="text-muted">Real reviews from verified bookings</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-box">
                    <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                    <h5>Guaranteed Quality</h5>
                    <p class="text-muted">Quality assurance on all services</p>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="feature-box">
                    <i class="fas fa-headset fa-3x text-info mb-3"></i>
                    <h5>24/7 Support</h5>
                    <p class="text-muted">Always here to help you</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Hero Styles */
.services-hero {
    background-image: url('https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=1920&h=1080&fit=crop');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 50vh;
    display: flex;
    align-items: center;
    position: relative;
}

.hero-bg-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(147,51,234,0.3) 100%);
    z-index: 0;
}

/* Category Tabs */
.category-tabs {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
}

.nav-pills .nav-link {
    color: var(--text-gray);
    background-color: transparent;
    border-radius: 30px;
    padding: 10px 25px;
    margin: 0 5px;
    transition: all 0.3s;
    border: 1px solid var(--border-dark);
    font-weight: 600;
}

.nav-pills .nav-link:hover {
    background-color: var(--bg-card-hover);
    color: var(--text-light);
    border-color: var(--primary-purple);
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
    color: white;
    border-color: transparent;
}

/* Service Categories */
.service-categories {
    background-color: var(--bg-darker);
    min-height: 600px;
}

/* Feature Box */
.feature-box {
    padding: 30px;
    transition: transform 0.3s;
}

.feature-box:hover {
    transform: translateY(-5px);
}

.feature-box i {
    opacity: 0.8;
}
</style>
@endpush

@push('scripts')
<script>
// Remember selected tab
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const parentCategory = urlParams.get('parent');
    
    if (parentCategory) {
        const tabButton = document.querySelector(`[data-bs-target="#${parentCategory}"]`);
        if (tabButton) {
            new bootstrap.Tab(tabButton).show();
        }
    }
});
</script>
@endpush

@endsection