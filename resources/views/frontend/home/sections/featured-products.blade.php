<section class="product-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white">Popular Equipment</h2>
            <p class="text-muted">Most rented items this month</p>
        </div>
        
        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-lg-3 col-md-6">
                    <x-product-card :product="$product" />
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('categories.index') }}" class="btn btn-primary">
                Browse All Equipment <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>