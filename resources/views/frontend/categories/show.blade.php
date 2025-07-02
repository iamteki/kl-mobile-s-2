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
            <div class="col-lg-3 mb-4 mb-lg-0">
                @include('frontend.categories.partials.filters')
            </div>

            <!-- Products Section -->
            <div class="col-lg-9">
                <!-- Sort Options -->
                <div class="products-header mb-4">
                    @include('frontend.categories.partials.sort-options')
                </div>
                
                <!-- Products Grid -->
                <div class="products-content">
                    @include('frontend.categories.partials.product-grid')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Category Header Styles */
.category-header {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
    padding: 60px 0;
    margin-bottom: 40px;
}

.category-icon {
    font-size: 48px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
    margin-bottom: 20px;
}

/* Main Layout Structure */
.container.my-5 {
    margin-top: 3rem !important;
    margin-bottom: 3rem !important;
}

/* Products Section */
.products-header {
    background-color: var(--bg-card);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
}

.products-content {
    min-height: 400px;
}

/* Ensure proper spacing between columns */
@media (min-width: 992px) {
    .row > .col-lg-3 {
        padding-right: 15px;
    }
    
    .row > .col-lg-9 {
        padding-left: 15px;
    }
}

/* Mobile Responsive */
@media (max-width: 991px) {
    .category-header {
        padding: 40px 0;
        margin-bottom: 20px;
    }
    
    .category-icon {
        font-size: 36px;
    }
}
</style>
@endpush

@push('scripts')
<script>
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
            if (filtersOverlay) {
                filtersOverlay.classList.toggle('show');
            }
            document.body.style.overflow = filtersColumn.classList.contains('show') ? 'hidden' : '';
        });
    }
    
    // Close mobile filters when clicking overlay
    if (filtersOverlay) {
        filtersOverlay.addEventListener('click', function() {
            filtersColumn.classList.remove('show');
            filtersOverlay.classList.remove('show');
            document.body.style.overflow = '';
        });
    }
    
    // Close button in mobile filters
    const closeBtn = document.querySelector('.filters-column .btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            filtersColumn.classList.remove('show');
            if (filtersOverlay) {
                filtersOverlay.classList.remove('show');
            }
            document.body.style.overflow = '';
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
            
            if (productGrid) {
                if (view === 'list') {
                    productGrid.classList.add('list-view');
                } else {
                    productGrid.classList.remove('list-view');
                }
            }
        });
    });
});
</script>
@endpush