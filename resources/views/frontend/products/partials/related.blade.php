<!-- Related Products -->
<div class="related-products">
    <h3 class="mb-4">Frequently Rented Together</h3>
    <div class="row g-4">
        @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6">
                <x-product-card :product="$relatedProduct" :show-quick-add="false" />
            </div>
        @endforeach
    </div>
</div>