@extends('layouts.app')

@section('title', 'Event Packages - KL Mobile')
@section('description', 'Choose from our pre-configured event packages designed for different event sizes and types. Complete solutions for your events.')

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title">Event Packages</h1>
                    <p class="page-subtitle">Complete solutions for every type of event</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Packages</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages-listing py-5">
        <div class="container">
            <!-- Category Intro -->
            <div class="text-center mb-5">
                <div class="icon-wrapper mx-auto mb-4">
                    <i class="fas fa-box-open"></i>
                </div>
                <h2 class="section-title">Choose Your Perfect Package</h2>
                <p class="section-description">
                    We've carefully curated packages to suit different event types and sizes. 
                    Each package includes everything you need for a successful event.
                </p>
            </div>

            <!-- Package Categories -->
            @if($packages->groupBy('category')->count() > 1)
                <div class="package-categories mb-5">
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <button class="btn btn-category active" data-category="all">
                            All Packages <span class="badge">{{ $packages->count() }}</span>
                        </button>
                        @foreach($packages->groupBy('category') as $category => $categoryPackages)
                            <button class="btn btn-category" data-category="{{ Str::slug($category) }}">
                                {{ $category }} <span class="badge">{{ $categoryPackages->count() }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Packages Grid -->
            <div class="row g-4" id="packagesGrid">
                @forelse($packages as $index => $package)
                    <div class="col-lg-4 col-md-6 package-item" data-category="{{ Str::slug($package->category) }}">
                        <x-package-card :package="$package" :featured="$package->featured" />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-4">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h3>No Packages Available</h3>
                            <p class="text-muted mb-4">We're currently updating our packages. Please check back soon!</p>
                            <a href="{{ route('contact') }}" class="btn btn-primary">
                                <i class="fas fa-phone me-2"></i>Contact Us
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Custom Package CTA -->
            <div class="custom-package-cta text-center mt-5 pt-5">
                <div class="cta-card">
                    <div class="row align-items-center">
                        <div class="col-lg-8 text-lg-start">
                            <h3 class="mb-3">Need a Custom Package?</h3>
                            <p class="text-muted mb-lg-0">
                                Can't find what you're looking for? We can create a custom package 
                                tailored to your specific event requirements and budget.
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-comments me-2"></i>Get Custom Quote
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="package-features mt-5">
                <h3 class="text-center mb-4">Why Choose Our Packages?</h3>
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <div class="feature-item text-center">
                            <div class="feature-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <h5>Best Value</h5>
                            <p class="text-muted small">Save up to 20% compared to individual rentals</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-item text-center">
                            <div class="feature-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h5>All Inclusive</h5>
                            <p class="text-muted small">Everything you need in one convenient package</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-item text-center">
                            <div class="feature-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h5>Free Delivery</h5>
                            <p class="text-muted small">Setup and delivery included in package price</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-item text-center">
                            <div class="feature-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h5>Full Support</h5>
                            <p class="text-muted small">Professional crew and technical support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    padding: 100px 0 50px;
    border-bottom: 1px solid var(--border-dark);
}

.page-title {
    font-size: 48px;
    font-weight: 800;
    color: var(--text-light);
    margin-bottom: 10px;
}

.page-subtitle {
    font-size: 20px;
    color: var(--text-gray);
}

/* Breadcrumb */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item {
    color: var(--text-gray);
}

.breadcrumb-item a {
    color: var(--primary-purple);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: var(--text-light);
}

/* Icon Wrapper */
.icon-wrapper {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.2) 0%, rgba(124, 58, 237, 0.2) 100%);
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-wrapper i {
    font-size: 48px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Section Title */
.section-title {
    font-size: 36px;
    font-weight: 700;
    color: var(--text-light);
    margin-bottom: 20px;
}

.section-description {
    font-size: 18px;
    color: var(--text-gray);
    max-width: 600px;
    margin: 0 auto;
}

/* Package Categories */
.package-categories {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
}

.btn-category {
    background: transparent;
    border: 1px solid var(--border-dark);
    color: var(--text-gray);
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-category:hover {
    border-color: var(--primary-purple);
    color: var(--primary-purple);
}

.btn-category.active {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    border-color: transparent;
    color: white;
}

.btn-category .badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: 5px;
    font-size: 12px;
}

/* Empty State */
.empty-state {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 60px 40px;
    border: 1px solid var(--border-dark);
}

.empty-icon {
    font-size: 80px;
    color: var(--text-muted);
    opacity: 0.3;
}

/* Custom Package CTA */
.cta-card {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    border: 1px solid var(--primary-purple);
    border-radius: 20px;
    padding: 40px;
}

/* Features */
.package-features {
    background: var(--bg-card);
    border-radius: 20px;
    padding: 40px;
    border: 1px solid var(--border-dark);
}

.feature-item {
    padding: 20px;
}

.feature-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.2) 0%, rgba(124, 58, 237, 0.2) 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.feature-icon i {
    font-size: 24px;
    color: var(--primary-purple);
}

.feature-item h5 {
    color: var(--text-light);
    font-weight: 600;
    margin-bottom: 10px;
}

/* Responsive */
@media (max-width: 991px) {
    .page-title {
        font-size: 36px;
    }
    
    .section-title {
        font-size: 28px;
    }
    
    .cta-card {
        text-align: center;
    }
}

@media (max-width: 767px) {
    .page-header {
        padding: 80px 0 40px;
    }
    
    .package-categories {
        padding: 15px;
    }
    
    .btn-category {
        font-size: 12px;
        padding: 8px 15px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category filtering
    const categoryButtons = document.querySelectorAll('.btn-category');
    const packageItems = document.querySelectorAll('.package-item');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter packages
            const selectedCategory = this.getAttribute('data-category');
            
            packageItems.forEach(item => {
                if (selectedCategory === 'all' || item.getAttribute('data-category') === selectedCategory) {
                    item.style.display = 'block';
                    // Add fade in animation
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.transition = 'opacity 0.3s';
                        item.style.opacity = '1';
                    }, 10);
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush