@extends('layouts.app')

@section('title', $category->name . ' - Professional Service Providers')
@section('description', $category->description)

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
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </div>
</nav>

<!-- Category Header -->
<section class="category-header py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="category-icon-large me-3">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                    <div>
                        <h1 class="mb-2">{{ $category->name }}</h1>
                        <p class="text-muted mb-0">{{ $category->description }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="category-stats">
                    <div class="stat-item">
                        <span class="stat-value">{{ $providers->total() }}</span>
                        <span class="stat-label">{{ Str::plural('Provider', $providers->total()) }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value">{{ number_format($providers->avg('rating'), 1) }}</span>
                        <span class="stat-label">Avg Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Providers -->
<section class="providers-section py-5">
    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                @include('frontend.services.partials.filters', ['category' => $category, 'languages' => $languages])
            </div>
            
            <!-- Providers Grid -->
            <div class="col-lg-9">
                <!-- Sort Bar -->
                <div class="sort-bar mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p class="mb-0">Showing {{ $providers->firstItem() ?? 0 }}-{{ $providers->lastItem() ?? 0 }} of {{ $providers->total() }} providers</p>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('services.category', $category->slug) }}" method="GET" id="sortForm">
                                @foreach(request()->except('sort') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                
                                <div class="d-flex align-items-center justify-content-md-end">
                                    <label class="me-2 text-nowrap">Sort by:</label>
                                    <select name="sort" class="form-select" onchange="document.getElementById('sortForm').submit()">
                                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Providers Grid -->
                <div class="row g-4">
                    @forelse($providers as $provider)
                        <div class="col-lg-6">
                            <x-service-provider-card :provider="$provider" />
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No providers found</h3>
                                <p class="text-muted">Try adjusting your filters or check back later.</p>
                                <a href="{{ route('services.index') }}" class="btn btn-primary mt-3">
                                    Browse All Services
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($providers->hasPages())
                    <div class="mt-5">
                        {{ $providers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Category Header */
.category-header {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    border-bottom: 1px solid var(--border-dark);
}

.category-icon-large {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.2) 0%, rgba(124, 58, 237, 0.2) 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-icon-large i {
    font-size: 36px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.category-stats {
    display: flex;
    gap: 30px;
    justify-content: flex-end;
}

.stat-item {
    text-align: center;
}

.stat-value {
    display: block;
    font-size: 32px;
    font-weight: 700;
    color: var(--primary-purple);
}

.stat-label {
    display: block;
    font-size: 14px;
    color: var(--text-gray);
}

/* Providers Section */
.providers-section {
    background-color: var(--bg-darker);
    min-height: 600px;
}

/* Sort Bar */
.sort-bar {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
}

.sort-bar .form-select {
    max-width: 200px;
}

/* Responsive */
@media (max-width: 991px) {
    .category-stats {
        justify-content: flex-start;
        margin-top: 20px;
    }
}
</style>
@endpush

@endsection