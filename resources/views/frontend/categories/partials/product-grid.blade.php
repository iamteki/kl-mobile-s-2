<!-- Products Grid -->
<div class="products-grid">
    @if($products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-lg-4 col-md-6">
                    <x-product-card :product="$product" :show-category="false" />
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <nav aria-label="Product pagination" class="mt-5">
                {{ $products->links('pagination::bootstrap-5') }}
            </nav>
        @endif
    @else
        <div class="no-products text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-4"></i>
            <h4 class="text-white mb-3">No products found</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['subcategory', 'brand', 'min_price', 'max_price', 'power_output']))
                    Try adjusting your filters to see more results.
                @else
                    This category doesn't have any products at the moment.
                @endif
            </p>
            @if(request()->hasAny(['subcategory', 'brand', 'min_price', 'max_price', 'power_output']))
                <a href="{{ route('category.show', $category->slug) }}" class="btn btn-primary">
                    <i class="fas fa-times me-2"></i>Clear Filters
                </a>
            @else
                <a href="{{ route('categories.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Browse All Categories
                </a>
            @endif
        </div>
    @endif
</div>

<style>
/* Pagination Override */
.pagination {
    --bs-pagination-padding-x: 18px;
    --bs-pagination-padding-y: 10px;
    --bs-pagination-color: var(--text-light);
    --bs-pagination-bg: var(--bg-card);
    --bs-pagination-border-color: var(--border-dark);
    --bs-pagination-hover-color: var(--text-light);
    --bs-pagination-hover-bg: var(--primary-purple);
    --bs-pagination-hover-border-color: var(--primary-purple);
    --bs-pagination-focus-box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
    --bs-pagination-active-color: #fff;
    --bs-pagination-active-bg: var(--primary-purple);
    --bs-pagination-active-border-color: var(--primary-purple);
    --bs-pagination-disabled-color: var(--text-gray);
    --bs-pagination-disabled-bg: var(--bg-dark);
    --bs-pagination-disabled-border-color: var(--border-dark);
    justify-content: center;
}

.page-link {
    border-radius: 8px;
    margin: 0 5px;
    transition: all 0.3s;
}

.page-link:hover {
    transform: translateY(-2px);
}

/* List View Styles */
.products-grid.list-view .row {
    display: flex;
    flex-direction: column;
}

.products-grid.list-view .col-lg-4,
.products-grid.list-view .col-md-6 {
    width: 100%;
    max-width: 100%;
}

.products-grid.list-view .product-card {
    display: flex;
    flex-direction: row;
    height: auto;
}

.products-grid.list-view .product-image {
    width: 250px;
    height: 200px;
    flex-shrink: 0;
}

.products-grid.list-view .product-info {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.products-grid.list-view .product-specs {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.products-grid.list-view .product-footer {
    border-top: none;
    padding-top: 0;
}

/* No Products State */
.no-products {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 60px 30px;
    border: 1px solid var(--border-dark);
}

@media (max-width: 768px) {
    .products-grid.list-view .product-card {
        flex-direction: column;
    }
    
    .products-grid.list-view .product-image {
        width: 100%;
        height: 250px;
    }
}
</style>