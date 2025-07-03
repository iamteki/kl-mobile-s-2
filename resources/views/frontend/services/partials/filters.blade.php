<!-- Filters Sidebar -->
<div class="filters-sidebar">
    <h3 class="filters-title mb-4">
        <i class="fas fa-filter me-2"></i>Filters
    </h3>
    
    <form action="{{ route('services.category', $category->slug) }}" method="GET" id="filtersForm">
        <!-- Preserve sort parameter -->
        @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif
        
        <!-- Experience Level -->
        <div class="filter-group">
            <h5 class="filter-heading">Experience Level</h5>
            <div class="filter-options">
                @foreach(['Entry' => 'Entry Level', 'Professional' => 'Professional', 'Premium' => 'Premium'] as $value => $label)
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="radio" 
                               name="experience_level" 
                               id="exp_{{ $value }}" 
                               value="{{ $value }}"
                               {{ request('experience_level') == $value ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="exp_{{ $value }}">
                            {{ $label }}
                        </label>
                    </div>
                @endforeach
                @if(request('experience_level'))
                    <a href="{{ route('services.category', array_merge(['category' => $category->slug], request()->except('experience_level'))) }}" 
                       class="clear-filter">
                        Clear
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Price Range -->
        <div class="filter-group">
            <h5 class="filter-heading">Price Range</h5>
            <div class="filter-options">
                @foreach(['budget' => 'Budget (Under 20K)', 'mid' => 'Mid-range (20K-50K)', 'premium' => 'Premium (50K+)'] as $value => $label)
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="radio" 
                               name="price_range" 
                               id="price_{{ $value }}" 
                               value="{{ $value }}"
                               {{ request('price_range') == $value ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="price_{{ $value }}">
                            {{ $label }}
                        </label>
                    </div>
                @endforeach
                @if(request('price_range'))
                    <a href="{{ route('services.category', array_merge(['category' => $category->slug], request()->except('price_range'))) }}" 
                       class="clear-filter">
                        Clear
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Rating -->
        <div class="filter-group">
            <h5 class="filter-heading">Minimum Rating</h5>
            <div class="filter-options">
                @foreach([4 => '4+ Stars', 3 => '3+ Stars', 2 => '2+ Stars'] as $value => $label)
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="radio" 
                               name="rating" 
                               id="rating_{{ $value }}" 
                               value="{{ $value }}"
                               {{ request('rating') == $value ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label d-flex align-items-center" for="rating_{{ $value }}">
                            <span class="me-2">{{ $label }}</span>
                            <span class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $value ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </span>
                        </label>
                    </div>
                @endforeach
                @if(request('rating'))
                    <a href="{{ route('services.category', array_merge(['category' => $category->slug], request()->except('rating'))) }}" 
                       class="clear-filter">
                        Clear
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Languages -->
        @if($languages->count() > 0)
            <div class="filter-group">
                <h5 class="filter-heading">Languages</h5>
                <div class="filter-options">
                    @foreach($languages as $language)
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="languages[]" 
                                   id="lang_{{ Str::slug($language) }}" 
                                   value="{{ $language }}"
                                   {{ in_array($language, (array)request('languages', [])) ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label class="form-check-label" for="lang_{{ Str::slug($language) }}">
                                {{ $language }}
                            </label>
                        </div>
                    @endforeach
                    @if(request('languages'))
                        <a href="{{ route('services.category', array_merge(['category' => $category->slug], request()->except('languages'))) }}" 
                           class="clear-filter">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        @endif
        
        <!-- Clear All Filters -->
        @if(request()->hasAny(['experience_level', 'price_range', 'rating', 'languages']))
            <div class="text-center mt-4">
                <a href="{{ route('services.category', $category->slug) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times me-2"></i>Clear All Filters
                </a>
            </div>
        @endif
    </form>
</div>

<style>
.filters-sidebar {
    background: var(--bg-card);
    padding: 25px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
    position: sticky;
    top: 100px;
}

.filters-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-light);
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-dark);
}

.filter-group {
    margin-bottom: 30px;
}

.filter-heading {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-light);
    margin-bottom: 15px;
}

.filter-options {
    position: relative;
}

.form-check {
    margin-bottom: 12px;
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
    cursor: pointer;
    transition: color 0.3s;
}

.form-check-label:hover {
    color: var(--text-light);
}

.clear-filter {
    display: inline-block;
    margin-top: 8px;
    font-size: 13px;
    color: var(--primary-purple);
    text-decoration: none;
}

.clear-filter:hover {
    color: var(--secondary-purple);
    text-decoration: underline;
}

.stars {
    font-size: 12px;
}
</style>