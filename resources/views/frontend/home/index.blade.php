@extends('layouts.app')

@section('title', 'KL Mobile - Event Equipment Rental & DJ Services')

@section('content')
    <!-- Hero Section -->
    @include('frontend.home.sections.hero')
    
    <!-- Search Section -->
    @include('frontend.home.sections.search')
    
    <!-- Categories Section -->
    @include('frontend.home.sections.categories')
    
    <!-- Featured Products Section -->
    @include('frontend.home.sections.featured-products')
    
    <!-- Packages Section -->
    @include('frontend.home.sections.packages')
    
    <!-- How It Works Section -->
    @include('frontend.home.sections.how-it-works')
    
    <!-- CTA Section -->
    @include('frontend.home.sections.cta')
@endsection

@push('styles')
<style>
    /* Homepage specific styles */
    .hero-section {
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 80%, rgba(147, 51, 234, 0.15) 0%, transparent 50%);
        z-index: -1;
    }

    .hero-title {
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .hero-title span {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .search-section {
        background-color: var(--bg-card);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        margin-top: -50px;
        position: relative;
        z-index: 10;
        border: 1px solid var(--border-dark);
    }

    .category-section {
        padding: 80px 0;
        background-color: var(--bg-black);
    }

    .product-section {
        background-color: var(--bg-dark);
        padding: 80px 0;
    }

    .package-section {
        background-color: var(--bg-black);
        padding: 80px 0;
    }

    .how-it-works {
        background-color: var(--bg-dark);
        padding: 80px 0;
    }

    .cta-section {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
        color: white;
        padding: 80px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 32px;
        }
        
        .search-section {
            padding: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Homepage specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any homepage specific functionality
    });
</script>
@endpush