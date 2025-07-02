<!-- Mobile Filter Toggle -->
<button class="mobile-filter-toggle d-lg-none">
    <i class="fas fa-filter me-2"></i>Show Filters
</button>

<!-- Filters Container -->
<div class="filters-wrapper">
    <div class="filters-column">
        <div class="filters-header d-lg-none">
            <h5>Filters</h5>
            <button class="btn-close btn-close-white" aria-label="Close filters"></button>
        </div>
        
        <form method="GET" action="{{ route('category.show', $category->slug) }}" id="filters-form">
            <div class="filters-section">
                <!-- Categories -->
                <div class="filter-group">
                    <h6>All Equipment Categories</h6>
                    <ul class="categories-list">
                        @foreach($allCategories as $cat)
                            <li>
                                <a href="{{ route('category.show', $cat->slug) }}" 
                                   class="{{ $cat->id === $category->id ? 'active' : '' }}">
                                    {{ $cat->name }}
                                    <span class="category-count">{{ $cat->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if(count($filters['subcategories']) > 0)
                    <!-- Subcategory Filter -->
                    <div class="filter-group">
                        <h6>Subcategory</h6>
                        <div class="filter-options">
                            @foreach($filters['subcategories'] as $subcategory)
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="subcategory[]" 
                                           value="{{ $subcategory['name'] }}"
                                           id="subcategory-{{ Str::slug($subcategory['name']) }}"
                                           {{ in_array($subcategory['name'], (array) request('subcategory')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="subcategory-{{ Str::slug($subcategory['name']) }}">
                                        {{ $subcategory['name'] }} 
                                        <span class="filter-count">({{ $subcategory['count'] }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($filters['brands']) > 0)
                    <!-- Brand Filter -->
                    <div class="filter-group">
                        <h6>Brand</h6>
                        <div class="filter-options">
                            @foreach($filters['brands'] as $brand)
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="brand[]" 
                                           value="{{ $brand['name'] }}"
                                           id="brand-{{ Str::slug($brand['name']) }}"
                                           {{ in_array($brand['name'], (array) request('brand')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="brand-{{ Str::slug($brand['name']) }}">
                                        {{ $brand['name'] }}
                                        <span class="filter-count">({{ $brand['count'] }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($filters['powerOutputs']) > 0)
                    <!-- Power Output Filter -->
                    <div class="filter-group">
                        <h6>Power Output</h6>
                        <div class="filter-options">
                            @foreach($filters['powerOutputs'] as $power)
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="power_output[]" 
                                           value="{{ $power['value'] }}"
                                           id="power-{{ Str::slug($power['value']) }}"
                                           {{ in_array($power['value'], (array) request('power_output')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="power-{{ Str::slug($power['value']) }}">
                                        {{ $power['range'] }}
                                        <span class="filter-count">({{ $power['count'] }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Price Range -->
                <div class="filter-group">
                    <h6>Price Range (per day)</h6>
                    <div class="price-range">
                        <input type="number" 
                               name="min_price" 
                               placeholder="Min" 
                               min="0"
                               value="{{ request('min_price') }}"
                               class="form-control">
                        <span class="price-separator">-</span>
                        <input type="number" 
                               name="max_price" 
                               placeholder="Max" 
                               min="0"
                               value="{{ request('max_price') }}"
                               class="form-control">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100 mt-3">
                        Apply Price Filter
                    </button>
                </div>

                <!-- Availability -->
                <div class="filter-group">
                    <h6>Availability</h6>
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="available_only" 
                               value="true"
                               id="available" 
                               {{ request('available_only') === 'true' ? 'checked' : '' }}>
                        <label class="form-check-label" for="available">
                            In Stock Only
                        </label>
                    </div>
                </div>

                <!-- Clear Filters -->
                @if(request()->hasAny(['subcategory', 'brand', 'min_price', 'max_price', 'power_output', 'available_only']))
                    <a href="{{ route('category.show', $category->slug) }}" 
                       class="btn btn-outline-primary w-100 mt-3">
                        <i class="fas fa-times me-2"></i>Clear All Filters
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Filters Overlay for Mobile -->
<div class="filters-overlay"></div>

<style>
/* Filters Wrapper */
.filters-wrapper {
    height: 100%;
}

/* Filters Section - No internal scrolling */
.filters-section {
    background-color: var(--bg-card);
    padding: 25px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
}

/* Filter Groups */
.filter-group {
    margin-bottom: 30px;
}

.filter-group:last-child {
    margin-bottom: 0;
}

.filter-group h6 {
    color: var(--text-light);
    font-weight: 600;
    margin-bottom: 15px;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 1px;
    border-bottom: 1px solid var(--border-dark);
    padding-bottom: 10px;
}

/* Filter Options - NO SCROLLING, all visible */
.filter-options {
    /* Remove max-height and overflow to show all options */
}

/* Form Check Styles */
.form-check {
    margin-bottom: 12px;
}

.form-check:last-child {
    margin-bottom: 0;
}

.form-check-input {
    background-color: var(--bg-dark);
    border-color: var(--border-dark);
    cursor: pointer;
}

.form-check-input:checked {
    background-color: var(--primary-purple);
    border-color: var(--primary-purple);
}

.form-check-input:focus {
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
}

.form-check-label {
    color: var(--text-gray);
    transition: color 0.3s;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.form-check-input:checked ~ .form-check-label {
    color: var(--text-light);
}

.filter-count {
    font-size: 12px;
    color: var(--text-gray);
}

/* Price Range */
.price-range {
    display: flex;
    gap: 10px;
    align-items: center;
}

.price-range input {
    background-color: var(--bg-dark);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    padding: 8px 12px;
    border-radius: 8px;
}

.price-range input:focus {
    border-color: var(--primary-purple);
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
}

.price-separator {
    color: var(--text-gray);
}

/* Categories List - All visible */
.categories-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.categories-list li {
    margin-bottom: 8px;
}

.categories-list li:last-child {
    margin-bottom: 0;
}

.categories-list a {
    color: var(--text-gray);
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    border-radius: 8px;
    transition: all 0.3s;
    border: 1px solid transparent;
}

.categories-list a:hover {
    background-color: var(--bg-dark);
    color: var(--secondary-purple);
    border-color: var(--border-dark);
}

.categories-list a.active {
    background-color: rgba(147, 51, 234, 0.1);
    color: var(--primary-purple);
    border-color: var(--primary-purple);
}

.category-count {
    background-color: var(--bg-dark);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

/* Mobile Filters */
.mobile-filter-toggle {
    display: none;
    background: var(--primary-purple);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    width: 100%;
    font-weight: 600;
    transition: all 0.3s;
}

.mobile-filter-toggle:hover {
    background: var(--accent-violet);
}

/* Make the page scrollable if filters are long */
@media (min-width: 992px) {
    /* Let the entire page scroll naturally */
    .filters-wrapper {
        position: relative;
    }
}

/* Mobile Responsive */
@media (max-width: 991px) {
    .mobile-filter-toggle {
        display: block;
        margin-bottom: 20px;
    }

    .filters-column {
        position: fixed;
        top: 0;
        left: -320px;
        width: 320px;
        height: 100vh;
        background-color: var(--bg-dark);
        z-index: 1050;
        overflow-y: auto; /* Allow scrolling on mobile since it's a modal */
        transition: left 0.3s ease;
        box-shadow: 2px 0 20px rgba(0,0,0,0.5);
    }

    .filters-column.show {
        left: 0;
    }

    .filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid var(--border-dark);
        background-color: var(--bg-card);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .filters-header h5 {
        margin: 0;
        color: var(--text-light);
    }

    .filters-section {
        border-radius: 0;
        border: none;
        border-bottom: 1px solid var(--border-dark);
    }

    .filters-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 1040;
        backdrop-filter: blur(5px);
    }

    .filters-overlay.show {
        display: block;
    }
}

/* Buttons */
.btn-primary {
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
    border: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(147, 51, 234, 0.4);
}

.btn-outline-primary {
    color: var(--primary-purple);
    border-color: var(--primary-purple);
    background: transparent;
    font-weight: 600;
}

.btn-outline-primary:hover {
    background: var(--primary-purple);
    border-color: var(--primary-purple);
    color: white;
}
</style>