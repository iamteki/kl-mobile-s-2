@props(['category'])

<div class="category-card">
    <a href="{{ route('category.show', $category->slug) }}" class="category-link">
        <div class="category-icon">
            <i class="{{ $category->icon }}"></i>
        </div>
        <h5 class="category-name">{{ $category->name }}</h5>
        <p class="category-count">{{ $category->products_count ?? 0 }} Items</p>
    </a>
</div>

<style>
.category-card {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid var(--border-dark);
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(147, 51, 234, 0.3);
    border-color: var(--primary-purple);
}

.category-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.category-icon {
    font-size: 48px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 15px;
}

.category-name {
    color: var(--text-light);
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}

.category-count {
    color: var(--text-gray);
    font-size: 14px;
    margin: 0;
}

@media (max-width: 768px) {
    .category-card {
        padding: 20px;
    }
    
    .category-icon {
        font-size: 36px;
    }
    
    .category-name {
        font-size: 16px;
    }
}
</style>