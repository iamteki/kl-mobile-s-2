<!-- Filters Row -->
<div class="filters-section mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-4">
            <label class="form-label text-muted small mb-1">Experience Level</label>
            <select class="form-select" id="experienceFilter">
                <option value="">All Levels</option>
                <option value="entry">Entry Level</option>
                <option value="professional">Professional</option>
                <option value="premium">Premium</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <label class="form-label text-muted small mb-1">Event Type</label>
            <select class="form-select" id="eventTypeFilter">
                <option value="">All Events</option>
                <option value="wedding">Weddings</option>
                <option value="corporate">Corporate Events</option>
                <option value="birthday">Birthday Parties</option>
                <option value="concert">Concerts & Shows</option>
                <option value="conference">Conferences</option>
                <option value="festival">Festivals</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <label class="form-label text-muted small mb-1">Sort By</label>
            <select class="form-select" id="sortFilter">
                <option value="featured">Featured</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
                <option value="popular">Most Popular</option>
            </select>
        </div>
    </div>
</div>

<style>
.filters-section {
    background: var(--bg-card);
    padding: 20px;
    border-radius: 15px;
    border: 1px solid var(--border-dark);
}

.filters-section .form-select {
    background: var(--bg-darker);
    border: 1px solid var(--border-dark);
    color: var(--text-light);
    padding: 10px 15px;
    font-size: 14px;
}

.filters-section .form-select:focus {
    background: var(--bg-darker);
    border-color: var(--primary-purple);
    color: var(--text-light);
    box-shadow: 0 0 0 0.25rem rgba(147, 51, 234, 0.25);
}

.filters-section .form-label {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter handling
    const filters = ['experienceFilter', 'eventTypeFilter', 'sortFilter'];
    
    filters.forEach(filterId => {
        const filter = document.getElementById(filterId);
        if (filter) {
            filter.addEventListener('change', function() {
                applyFilters();
            });
        }
    });
    
    function applyFilters() {
        const experience = document.getElementById('experienceFilter').value;
        const eventType = document.getElementById('eventTypeFilter').value;
        const sort = document.getElementById('sortFilter').value;
        
        // Build URL parameters
        const params = new URLSearchParams(window.location.search);
        
        if (experience) params.set('experience', experience);
        else params.delete('experience');
        
        if (eventType) params.set('event_type', eventType);
        else params.delete('event_type');
        
        if (sort) params.set('sort', sort);
        else params.delete('sort');
        
        // Reload page with new parameters
        window.location.href = window.location.pathname + '?' + params.toString();
    }
    
    // Set current filter values from URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('experience')) {
        document.getElementById('experienceFilter').value = urlParams.get('experience');
    }
    if (urlParams.get('event_type')) {
        document.getElementById('eventTypeFilter').value = urlParams.get('event_type');
    }
    if (urlParams.get('sort')) {
        document.getElementById('sortFilter').value = urlParams.get('sort');
    }
});
</script>
@endpush