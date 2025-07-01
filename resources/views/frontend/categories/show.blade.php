@extends('layouts.app')

@section('title', $category->name . ' - KL Mobile Equipment Rental')
@section('description', $category->description ?? 'Professional ' . $category->name . ' rental in Kuala Lumpur. Check availability and book instantly with real-time inventory.')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Equipment</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
@endsection

@section('content')
    <!-- Category Header -->
    <section class="category-header">
        <div class="container text-center">
            <i class="{{ $category->icon }} category-icon"></i>
            <h1 class="text-white mb-3">{{ $category->name }} Rental</h1>
            <p class="text-muted lead">{{ $category->description ?? 'Professional equipment for events of all sizes' }}</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                @include('frontend.categories.partials.filters')
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                @include('frontend.categories.partials.sort-options')
                @include('frontend.categories.partials.product-grid')
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Category specific styles are already in the main layout */
</style>
@endpush

@push('scripts')
<script>
    // Category page specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Handle filter changes
        const filterInputs = document.querySelectorAll('.filter-section input[type="checkbox"], .filter-section input[type="number"]');
        
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                // You could implement AJAX filtering here
                // For now, we'll use form submission
            });
        });
        
        // Handle mobile filter toggle
        const mobileFilterToggle = document.querySelector('.mobile-filter-toggle');
        const filtersColumn = document.querySelector('.filters-column');
        const filtersOverlay = document.querySelector('.filters-overlay');
        
        if (mobileFilterToggle) {
            mobileFilterToggle.addEventListener('click', function() {
                filtersColumn.classList.toggle('show');
                filtersOverlay.classList.toggle('show');
            });
        }
        
        if (filtersOverlay) {
            filtersOverlay.addEventListener('click', function() {
                filtersColumn.classList.remove('show');
                filtersOverlay.classList.remove('show');
            });
        }
        
        // Handle view toggle
        const viewButtons = document.querySelectorAll('.view-btn');
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.dataset.view;
                const productGrid = document.querySelector('.products-grid');
                
                if (view === 'list') {
                    productGrid.classList.add('list-view');
                } else {
                    productGrid.classList.remove('list-view');
                }
            });
        });
    });
</script>
@endpush