@extends('layouts.app')

@section('title', 'Professional Event Services')
@section('description', 'Hire professional DJs, photographers, videographers, emcees, and event staff for your events in Sri Lanka.')

@section('content')
<!-- Hero Section -->
@include('frontend.services.partials.hero')

<!-- Services Content -->
<section class="services-content py-5">
    <div class="container">
        <!-- Service Category Tabs -->
        <div class="service-tabs mb-5">
            <ul class="nav nav-pills justify-content-center mb-4" id="serviceTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" 
                            id="all-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#all" 
                            type="button" 
                            role="tab" 
                            aria-controls="all" 
                            aria-selected="true">
                        All Services ({{ $services->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="entertainment-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#entertainment" 
                            type="button" 
                            role="tab" 
                            aria-controls="entertainment" 
                            aria-selected="false">
                        Entertainment ({{ $categoryCounts['Entertainment'] ?? 0 }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="technical-crew-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#technical-crew" 
                            type="button" 
                            role="tab" 
                            aria-controls="technical-crew" 
                            aria-selected="false">
                        Technical Crew ({{ $categoryCounts['Technical Crew'] ?? 0 }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="media-production-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#media-production" 
                            type="button" 
                            role="tab" 
                            aria-controls="media-production" 
                            aria-selected="false">
                        Media Production ({{ $categoryCounts['Media Production'] ?? 0 }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" 
                            id="event-staff-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#event-staff" 
                            type="button" 
                            role="tab" 
                            aria-controls="event-staff" 
                            aria-selected="false">
                        Event Staff ({{ $categoryCounts['Event Staff'] ?? 0 }})
                    </button>
                </li>
            </ul>
        </div>

        <!-- Filters Row -->
        @include('frontend.services.partials.filters')

        <!-- Services Grid -->
        <div class="tab-content" id="serviceTabContent">
            <!-- All Services Tab -->
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="row g-4">
                    @forelse($services as $service)
                        <div class="col-lg-4 col-md-6">
                            <x-service-card :service="$service" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No services available</h3>
                                <p class="text-muted">Check back later for our professional services.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Entertainment Tab -->
            <div class="tab-pane fade" id="entertainment" role="tabpanel" aria-labelledby="entertainment-tab">
                <div class="row g-4">
                    @forelse($services->where('category', 'Entertainment') as $service)
                        <div class="col-lg-4 col-md-6">
                            <x-service-card :service="$service" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-music fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No entertainment services available</h3>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Technical Crew Tab -->
            <div class="tab-pane fade" id="technical-crew" role="tabpanel" aria-labelledby="technical-crew-tab">
                <div class="row g-4">
                    @forelse($services->where('category', 'Technical Crew') as $service)
                        <div class="col-lg-4 col-md-6">
                            <x-service-card :service="$service" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-tools fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No technical crew services available</h3>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Media Production Tab -->
            <div class="tab-pane fade" id="media-production" role="tabpanel" aria-labelledby="media-production-tab">
                <div class="row g-4">
                    @forelse($services->where('category', 'Media Production') as $service)
                        <div class="col-lg-4 col-md-6">
                            <x-service-card :service="$service" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-camera fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No media production services available</h3>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Event Staff Tab -->
            <div class="tab-pane fade" id="event-staff" role="tabpanel" aria-labelledby="event-staff-tab">
                <div class="row g-4">
                    @forelse($services->where('category', 'Event Staff') as $service)
                        <div class="col-lg-4 col-md-6">
                            <x-service-card :service="$service" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-user-tie fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No event staff services available</h3>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
@include('frontend.services.partials.cta')

@push('styles')
<style>
/* Tab Styles */
.nav-pills .nav-link {
    color: var(--text-gray);
    background-color: var(--bg-card);
    border-radius: 30px;
    padding: 12px 30px;
    margin: 0 5px;
    transition: all 0.3s;
    border: 1px solid var(--border-dark);
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
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

/* Services Content */
.services-content {
    background-color: var(--bg-darker);
    min-height: 600px;
}
</style>
@endpush

@endsection