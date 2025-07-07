<div class="product-images">
    <div class="main-image-container">
        <img src="{{ $product->main_image_url ?? 'https://via.placeholder.com/800x600' }}" 
             alt="{{ $product->name }}" 
             class="main-image" 
             id="mainImage">
        
        @if($product->badges)
            <div class="image-badges">
                @foreach($product->badges as $badge)
                    <span class="badge-custom">{{ $badge }}</span>
                @endforeach
            </div>
        @endif
        
        <div class="zoom-hint">
            <i class="fas fa-search-plus"></i>
            <span>Hover to zoom</span>
        </div>
    </div>
    
    @if(count($product->gallery_images) > 0)
        <div class="thumbnail-container">
            <div class="thumbnail active" onclick="changeImage('{{ $product->main_image_url }}')">
                <img src="{{ $product->main_image_url }}" alt="Main view">
            </div>
            @foreach($product->gallery_images as $index => $image)
            <div class="thumbnail" onclick="changeImage('{{ $image['url'] }}')">
                <img src="{{ $image['thumb'] ?? $image['url'] }}" 
                    alt="View {{ $index + 2 }}"
                    onerror="this.src='{{ $image['url'] }}'">
            </div>
        @endforeach
        </div>
    @endif
</div>