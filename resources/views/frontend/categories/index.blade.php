@extends('layouts.app')

@section('title', 'Equipment Categories - KL Mobile')
@section('description', 'Browse our complete inventory of professional event equipment. Sound systems, lighting, LED screens, DJ equipment, and more available for rent.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Equipment Categories</li>
@endsection

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container text-center">
            <h1 class="text-white mb-3">Equipment Categories</h1>
            <p class="text-muted lead">Everything you need for successful events</p>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="categories-page py-5">
        <div class="container">
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-lg-4 col-md-6">
                        <div class="category-card-large">
                            <a href="{{ route('category.show', $category->slug) }}" class="category-link">
                                <div class="category-icon">
                                    <i class="{{ $category->icon }}"></i>
                                </div>
                                <div class="category-content">
                                    <h3 class="category-name">{{ $category->name }}</h3>
                                    <p class="category-description">{{ $category->description }}</p>
                                    <div class="category-meta">
                                        <span class="product-count">{{ $category->products_count }} Products</span>
                                        <span class="view-arrow">
                                            View Category <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="category-cta">
        <div class="container text-center">
            <h2 class="text-white mb-4">Can't find what you're looking for?</h2>
            <p class="text-muted mb-4">Contact our team for custom equipment solutions</p>
            <a href="{{ route('contact') }}" class="btn btn-primary">
                <i class="fas fa-phone me-2"></i>Contact Us
            </a>
        </div>
    </section>
@endsection

@push('styles')
<style>
.page-header {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    padding: 60px 0;
    margin-bottom: 40px;
}

.categories-page {
    background-color: var(--bg-black);
}

.category-card-large {
    background: var(--bg-card);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid var(--border-dark);
}

.category-card-large:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 40px rgba(147, 51, 234, 0.3);
    border-color: var(--primary-purple);
}

.category-link {
    display: block;
    padding: 40px;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.category-icon {
    font-size: 48px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
}

.category-name {
    color: var(--text-light);
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 15px;
}

.category-description {
    color: var(--text-gray);
    margin-bottom: 20px;
    line-height: 1.6;
}

.category-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid var(--border-dark);
}

.product-count {
    color: var(--primary-purple);
    font-weight: 600;
}

.view-arrow {
    color: var(--text-gray);
    font-size: 14px;
    transition: all 0.3s ease;
}

.category-card-large:hover .view-arrow {
    color: var(--secondary-purple);
    transform: translateX(5px);
}

.category-cta {
    background-color: var(--bg-dark);
    padding: 80px 0;
}

@media (max-width: 768px) {
    .category-link {
        padding: 25px;
    }
    
    .category-icon {
        font-size: 36px;
    }
    
    .category-name {
        font-size: 20px;
    }
}
</style>
@endpush