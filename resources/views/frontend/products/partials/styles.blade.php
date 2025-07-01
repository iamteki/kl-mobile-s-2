<style>
/* Product Images Section */
.product-images {
    position: sticky;
    top: 20px;
}

.main-image-container {
    background: var(--bg-card);
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: 20px;
    position: relative;
    border: 1px solid var(--border-dark);
}

.main-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.image-badges {
    position: absolute;
    top: 20px;
    left: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.zoom-hint {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.thumbnail-container {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 10px;
}

.thumbnail {
    flex-shrink: 0;
    width: 100px;
    height: 100px;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumbnail.active,
.thumbnail:hover {
    border-color: var(--primary-purple);
}

/* Product Info Section */
.product-info-section {
    padding: 0 0 0 30px;
}

.product-category {
    color: var(--primary-purple);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.product-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text-light);
}

.product-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.sku {
    color: var(--text-gray);
    font-size: 14px;
}

.availability-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.availability-status.in-stock {
    color: var(--success-green);
}

.availability-status.low-stock {
    color: var(--warning-yellow);
}

.availability-status.out-stock {
    color: var(--danger-red);
}

/* Pricing Section */
.pricing-section {
    background: var(--bg-card);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    border: 1px solid var(--border-dark);
}

.price-display {
    font-size: 36px;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 10px;
}

.price-note {
    color: var(--text-gray);
    font-size: 14px;
    margin-bottom: 20px;
}

.pricing-tiers {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.tier-card {
    flex: 1;
    padding: 15px;
    background: var(--bg-dark);
    border-radius: 10px;
    text-align: center;
    border: 1px solid var(--border-dark);
    transition: all 0.3s;
    cursor: pointer;
}

.tier-card:hover {
    border-color: var(--primary-purple);
    transform: translateY(-2px);
}

.tier-days {
    font-size: 14px;
    color: var(--text-gray);
    margin-bottom: 5px;
}

.tier-price {
    font-size: 18px;
    font-weight: 600;
    color: var(--secondary-purple);
}

.tier-save {
    font-size: 12px;
    color: var(--success-green);
    margin-top: 5px;
}

/* Tabs Section */
.tabs-section {
    margin-top: 60px;
}

.nav-tabs {
    border-bottom: 1px solid var(--border-dark);
    gap: 30px;
}

.nav-tabs .nav-link {
    color: var(--text-gray);
    border: none;
    padding: 15px 0;
    font-weight: 600;
    position: relative;
    transition: all 0.3s;
}

.nav-tabs .nav-link:hover {
    color: var(--secondary-purple);
    border: none;
}

.nav-tabs .nav-link.active {
    color: var(--text-light);
    background: transparent;
    border: none;
}

.nav-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary-purple);
}

.tab-content {
    padding: 30px 0;
}

/* Specifications Table */
.specs-table {
    background: var(--bg-card);
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid var(--border-dark);
}

.specs-table table {
    width: 100%;
    border-collapse: collapse;
}

.specs-table th,
.specs-table td {
    padding: 15px 20px;
    text-align: left;
    border-bottom: 1px solid var(--border-dark);
}

.specs-table th {
    background: var(--bg-dark);
    color: var(--text-gray);
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.specs-table td:first-child {
    color: var(--text-gray);
    width: 40%;
}

.specs-table tr:last-child td {
    border-bottom: none;
}

/* Features Grid */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.feature-item {
    display: flex;
    gap: 15px;
    padding: 20px;
    background: var(--bg-card);
    border-radius: 10px;
    border: 1px solid var(--border-dark);
}

.feature-icon {
    width: 40px;
    height: 40px;
    background: rgba(147, 51, 234, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.feature-icon i {
    color: var(--primary-purple);
}

.feature-content h6 {
    color: var(--text-light);
    margin-bottom: 5px;
}

.feature-content p {
    color: var(--text-gray);
    font-size: 14px;
    margin: 0;
}

/* Related Products */
.related-products {
    margin-top: 80px;
}

/* Responsive */
@media (max-width: 991px) {
    .product-info-section {
        padding: 30px 0 0 0;
    }

    .product-images {
        position: relative;
    }
}

@media (max-width: 768px) {
    .product-title {
        font-size: 24px;
    }

    .price-display {
        font-size: 28px;
    }

    .pricing-tiers {
        flex-direction: column;
    }
}
</style>