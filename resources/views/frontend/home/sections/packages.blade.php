<section class="package-section" id="packages">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white">Event Packages</h2>
            <p class="text-muted">Complete solutions for your events</p>
        </div>
        
        <div class="row g-4">
            @foreach($packages as $index => $package)
                <div class="col-lg-4">
                    <x-package-card :package="$package" :featured="$index === 1" />
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <p class="text-muted mb-3">Need a custom package for your event?</p>
            <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                <i class="fas fa-phone me-2"></i>Contact Us for Custom Quote
            </a>
        </div>
    </div>
</section>