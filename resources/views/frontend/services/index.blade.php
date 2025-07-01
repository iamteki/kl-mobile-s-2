@extends('layouts.app')

@section('title', 'Professional Event Services - KL Mobile')
@section('description', 'Book professional DJs, emcees, live bands, photographers, videographers, and event staff. Expert event services in Kuala Lumpur with instant booking.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Professional Services</li>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold text-white mb-3">Professional Event Services</h1>
            <p class="lead text-gray">Expert DJs, emcees, live bands, photographers, and complete event staffing solutions</p>
        </div>
    </section>

    <!-- Service Categories Tabs -->
    <div class="container my-5">
        @include('frontend.services.partials.service-tabs')
        @include('frontend.services.partials.filters')
        @include('frontend.services.partials.service-grid')
    </div>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">150+</div>
                        <div class="stat-label">Professional Artists</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Events Serviced</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mt-4 mt-md-0">
                    <div class="stat-box">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Live Bands</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mt-4 mt-md-0">
                    <div class="stat-box">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support Available</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container text-center position-relative">
            <h2 class="text-white mb-4">Need Multiple Services for Your Event?</h2>
            <p class="lead text-muted mb-5">Check out our complete event packages with special bundle pricing</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('packages.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-box me-2"></i>View Packages
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-phone me-2"></i>Talk to Expert
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Page specific styles are already included in the layout */
</style>
@endpush

@push('scripts')
<script>
    // Filter functionality
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from siblings
            this.parentElement.querySelectorAll('.filter-btn').forEach(sibling => {
                sibling.classList.remove('active');
            });
            // Add active class to clicked button
            this.classList.add('active');
            
            // Here you would filter the services
            // For now, we'll just log the action
            console.log('Filter:', this.textContent);
        });
    });
</script>
@endpush