<!-- Service Category Card -->
<div class="service-category-card h-100">
    <div class="card-image">
        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" loading="lazy">
        <div class="card-overlay">
            <a href="{{ route('services.category', $category->slug) }}" class="btn btn-primary">
                View Providers
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="card-icon">
            <i class="{{ $category->icon }}"></i>
        </div>
        <h4 class="card-title">{{ $category->name }}</h4>
        <p class="card-description">{{ $category->description }}</p>
        <div class="card-footer">
            <div class="provider-count">
                <i class="fas fa-users me-1"></i>
                {{ $category->active_providers_count }} 
                {{ Str::plural('Provider', $category->active_providers_count) }}
            </div>
            <a href="{{ route('services.category', $category->slug) }}" class="view-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<style>
.service-category-card {
    background: var(--bg-card);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s;
    border: 1px solid var(--border-dark);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.service-category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(147, 51, 234, 0.3);
    border-color: var(--primary-purple);
}

.card-image {
    height: 250px;
    position: relative;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.service-category-card:hover .card-image img {
    transform: scale(1.1);
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.service-category-card:hover .card-overlay {
    opacity: 1;
}

.card-body {
    padding: 30px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.2) 0%, rgba(124, 58, 237, 0.2) 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.card-icon i {
    font-size: 24px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.card-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--text-light);
}

.card-description {
    color: var(--text-gray);
    margin-bottom: 20px;
    flex: 1;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid var(--border-dark);
}

.provider-count {
    color: var(--text-gray);
    font-size: 14px;
}

.view-link {
    color: var(--primary-purple);
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s;
}

.view-link:hover {
    color: var(--secondary-purple);
    transform: translateX(5px);
}
</style>