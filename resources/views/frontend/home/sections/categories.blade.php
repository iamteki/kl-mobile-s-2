<section class="category-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white">Equipment Categories</h2>
            <p class="text-muted">Browse our extensive inventory of professional event equipment</p>
        </div>
        
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-lg-3 col-md-6">
                    <x-category-card :category="$category" />
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                View All Categories <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>