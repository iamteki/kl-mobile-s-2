<button class="mobile-filter-toggle">
    <i class="fas fa-filter me-2"></i>Show Filters
</button>

<div class="filters-column">
    <button class="btn btn-close btn-close-white float-end d-lg-none mb-3"></button>
    
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
                    @foreach($filters['subcategories'] as $subcategory)
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="subcategory[]" 
                                   value="{{ $subcategory['name'] }}"
                                   id="subcategory-{{ Str::slug($subcategory['name']) }}"
                                   {{ in_array($subcategory['name'], (array) request('subcategory')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="subcategory-{{ Str::slug($subcategory['name']) }}">
                                {{ $subcategory['name'] }} ({{ $subcategory['count'] }})
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($filters['brands']) > 0)
                <!-- Brand Filter -->
                <div class="filter-group">
                    <h6>Brand</h6>
                    @foreach($filters['brands'] as $brand)
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="brand[]" 
                                   value="{{ $brand['name'] }}"
                                   id="brand-{{ Str::slug($brand['name']) }}"
                                   {{ in_array($brand['name'], (array) request('brand')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="brand-{{ Str::slug($brand['name']) }}">
                                {{ $brand['name'] }} ({{ $brand['count'] }})
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(count($filters['powerOutputs']) > 0)
                <!-- Power Output Filter -->
                <div class="filter-group">
                    <h6>Power Output</h6>
                    @foreach($filters['powerOutputs'] as $power)
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="power_output[]" 
                                   value="{{ $power['value'] }}"
                                   id="power-{{ Str::slug($power['value']) }}"
                                   {{ in_array($power['value'], (array) request('power_output')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="power-{{ Str::slug($power['value']) }}">
                                {{ $power['range'] }} ({{ $power['count'] }})
                            </label>
                        </div>
                    @endforeach
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
                           value="{{ request('min_price') }}">
                    <span style="color: var(--text-gray);">-</span>
                    <input type="number" 
                           name="max_price" 
                           placeholder="Max" 
                           min="0"
                           value="{{ request('max_price') }}">
                </div>
                <button type="submit" class="btn btn-sm btn-primary w-100 mt-3">Apply</button>
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
            <a href="{{ route('category.show', $category->slug) }}" 
               class="btn btn-outline-primary w-100 mt-3">
                <i class="fas fa-times me-2"></i>Clear All Filters
            </a>
        </div>
    </form>
</div>

<!-- Filters Overlay for Mobile -->
<div class="filters-overlay"></div>

<style>
/* Filters Section */
.filters-section {
    background-color: var(--bg-card);
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    border: 1px solid var(--border-dark);
}

.filter-group {
    margin-bottom: 25px;
}

.filter-group h6 {
    color: var(--text-light);
    font-weight: 600;
    margin-bottom: 15px;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 1px;
}

.form-check {
    margin-bottom: 10px;
}

.form-check-input {
    background-color: var(--bg-dark);
    border-color: var(--border-dark);
}

.form-check-input:checked {
    background-color: var(--primary-purple);
    border-color: var(--primary-purple);
}

.form-check-label {
    color: var(--text-gray);
    transition: color 0.3s;
}

.form-check-input:checked ~ .form-check-label {
    color: var(--text-light);
}

.price-range {
    display: flex;
    gap: 10px;
    align-items: center;
}

.price-range input {
    background-color: var(--bg-dark);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    padding: 8px;
    border-radius: 5px;
    width: 100%;
}

.price-range input:focus {
    border-color: var(--primary-purple);
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
}

/* Mobile Filters Toggle */
.mobile-filter-toggle {
    display: none;
    background: var(--primary-purple);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    width: 100%;
}

/* Categories Sidebar */
.categories-list {
    list-style: none;
    padding: 0;
}

.categories-list li {
    margin-bottom: 10px;
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
}

.categories-list a:hover,
.categories-list a.active {
    background-color: var(--bg-dark);
    color: var(--secondary-purple);
}

.category-count {
    background-color: var(--bg-dark);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
}

/* Responsive */
@media (max-width: 991px) {
    .mobile-filter-toggle {
        display: block;
    }

    .filters-column {
        position: fixed;
        top: 0;
        left: -100%;
        width: 300px;
        height: 100vh;
        background-color: var(--bg-dark);
        z-index: 1050;
        overflow-y: auto;
        transition: left 0.3s;
        padding: 20px;
    }

    .filters-column.show {
        left: 0;
    }

    .filters-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1040;
    }

    .filters-overlay.show {
        display: block;
    }
}