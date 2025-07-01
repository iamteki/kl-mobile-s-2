@props(['category'])

<div class="category-card" onclick="window.location.href='{{ route('category.show', $category->slug) }}'">
    <i class="{{ $category->icon ?? 'fas fa-box' }}"></i>
    <h4>{{ $category->name }}</h4>
    <p class="text-muted">{{ $category->short_description ?? $category->products_count . ' items available' }}</p>
    <a href="{{ route('category.show', $category->slug) }}" class="btn btn-outline-primary btn-sm">
        View All
    </a>
</div>

<style>
.category-card {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    transition: all 0.3s;
    height: 100%;
    cursor: pointer;
    border: 1px solid var(--border-dark);
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(147, 51, 234, 0.3);
    border-color: var(--primary-purple);
    background: var(--bg-card-hover);
}

.category-card i {
    font-size: 48px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
}

.category-card h4 {
    color: var(--text-light);
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
}

.category-card p {
    color: var(--text-gray);
    margin-bottom: 20px;
}
</style>