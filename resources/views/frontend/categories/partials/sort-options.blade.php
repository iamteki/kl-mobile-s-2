<!-- Sort and View Options -->
<div class="sort-section">
    <div class="results-count">
        Showing {{ $products->count() }} of {{ $products->total() }} results
    </div>
    <div class="sort-options">
        <form method="GET" action="{{ route('category.show', $category->slug) }}" class="d-flex gap-3">
            <!-- Preserve existing filters -->
            @foreach(request()->except(['sort', 'page']) as $key => $value)
                @if(is_array($value))
                    @foreach($value as $val)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $val }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            
            <select class="form-select" name="sort" onchange="this.form.submit()">
                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Sort by: Featured</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
            </select>
        </form>
        
        <div class="view-options">
            <button class="view-btn active" data-view="grid" title="Grid View">
                <i class="fas fa-th"></i>
            </button>
            <button class="view-btn" data-view="list" title="List View">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>
</div>

<style>
/* Sort and View Options */
.sort-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}

.results-count {
    color: var(--text-gray);
}

.sort-options {
    display: flex;
    gap: 15px;
    align-items: center;
}

.form-select {
    background-color: var(--bg-card);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    padding: 8px 15px;
    min-width: 200px;
}

.form-select:focus {
    background-color: var(--bg-card);
    border-color: var(--primary-purple);
    color: var(--text-light);
    box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
}

.view-options {
    display: flex;
    gap: 10px;
}

.view-btn {
    background-color: var(--bg-card);
    border: 1px solid var(--border-dark);
    color: var(--text-gray);
    padding: 8px 12px;
    border-radius: 5px;
    transition: all 0.3s;
    cursor: pointer;
}

.view-btn.active,
.view-btn:hover {
    background-color: var(--primary-purple);
    border-color: var(--primary-purple);
    color: white;
}

@media (max-width: 768px) {
    .sort-section {
        flex-direction: column;
        align-items: stretch;
    }

    .form-select {
        width: 100%;
    }
}
</style>