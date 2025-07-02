<!-- Complete styles.blade.php file -->
<!-- If using Bootstrap from CDN (current setup) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- If using Bootstrap from npm, remove the CDN link above and uncomment the import in app.css -->

<style>
    :root {
        --primary-purple: #9333EA;
        --secondary-purple: #C084FC;
        --accent-violet: #7C3AED;
        --accent-indigo: #6366F1;
        --light-purple: #E9D5FF;
        --bg-black: #000000;
        --bg-dark: #0A0A0A;
        --bg-darker: #050505;
        --bg-card: #141414;
        --bg-card-hover: #1A1A1A;
        --text-light: #F8F9FA;
        --text-gray: #9CA3AF;
        --border-dark: #2A2A2A;
        --border-light: #3A3A3A;
        --success-green: #22C55E;
        --warning-yellow: #F59E0B;
        --danger-red: #EF4444;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: var(--bg-black);
        color: var(--text-light);
        line-height: 1.6;
    }

    /* Header */
    .header-top {
        background-color: var(--bg-darker);
        color: var(--text-gray);
        padding: 10px 0;
        font-size: 14px;
        border-bottom: 1px solid var(--border-dark);
    }

    .header-top i {
        color: var(--primary-purple);
    }

    /* Navigation */
    .navbar {
        background-color: var(--bg-dark) !important;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-dark);
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 28px;
        color: var(--primary-purple) !important;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .navbar-nav .nav-link {
        color: var(--text-light) !important;
        font-weight: 500;
        margin: 0 15px;
        transition: all 0.3s;
        position: relative;
    }

    .navbar-nav .nav-link:hover {
        color: var(--secondary-purple) !important;
    }

    .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary-purple);
        transform: scaleX(0);
        transition: transform 0.3s;
    }

    .navbar-nav .nav-link:hover::after,
    .navbar-nav .nav-link.active::after {
        transform: scaleX(1);
    }

    .dropdown-menu {
        background-color: var(--bg-card);
        border: 1px solid var(--border-dark);
    }

    .dropdown-item {
        color: var(--text-light);
        transition: all 0.3s;
    }

    .dropdown-item:hover {
        background-color: var(--bg-card-hover);
        color: var(--secondary-purple);
    }

    /* Breadcrumb */
    .breadcrumb-section {
        background-color: var(--bg-dark);
        padding: 20px 0;
        border-bottom: 1px solid var(--border-dark);
    }

    .breadcrumb {
        margin: 0;
        background: transparent;
    }

    .breadcrumb-item {
        color: var(--text-gray);
    }

    .breadcrumb-item a {
        color: var(--text-gray);
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb-item a:hover {
        color: var(--secondary-purple);
    }

    .breadcrumb-item.active {
        color: var(--text-light);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        color: var(--text-gray);
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
        border: none;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(147, 51, 234, 0.4);
        background: linear-gradient(135deg, var(--accent-violet) 0%, var(--primary-purple) 100%);
    }

    .btn-outline-primary {
        color: var(--primary-purple);
        border-color: var(--primary-purple);
        background: transparent;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-purple);
        border-color: var(--primary-purple);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(147, 51, 234, 0.3);
    }

    .btn-outline-light {
        color: var(--text-light);
        border-color: var(--text-light);
        background: transparent;
    }

    .btn-outline-light:hover {
        background-color: var(--text-light);
        border-color: var(--text-light);
        color: var(--bg-dark);
    }

    /* Footer */
    footer {
        background-color: var(--bg-darker);
        color: var(--text-light);
        padding: 50px 0 20px;
        border-top: 1px solid var(--border-dark);
        margin-top: 80px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: var(--text-gray);
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: var(--secondary-purple);
    }

    .social-icons a {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        margin-right: 10px;
        transition: all 0.3s;
        color: var(--text-gray);
    }

    .social-icons a:hover {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--accent-violet) 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
    }

    /* Utilities */
    .text-muted {
        color: var(--text-gray) !important;
    }

    .text-white {
        color: var(--text-light) !important;
    }

    /* Loading States */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid var(--border-dark);
        border-radius: 50%;
        border-top-color: var(--primary-purple);
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navbar-nav {
            text-align: center;
        }
        
        .navbar-nav .nav-link {
            margin: 5px 0;
        }
        
        footer .text-md-end {
            text-align: center !important;
            margin-top: 15px;
        }
    }

    /* ========================================= */
    /* CATEGORY PAGE GRID FIXES - CRITICAL SECTION */
    /* ========================================= */

    /* Category Header */
    .category-header {
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
        padding: 60px 0;
        margin-bottom: 40px;
        text-align: center;
    }

    .category-header .category-icon {
        font-size: 72px;
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
        margin-bottom: 20px;
    }

    /* Product Card Styles */
    .product-card {
        background: var(--bg-card);
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid var(--border-dark);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(147, 51, 234, 0.3);
        border-color: var(--primary-purple);
    }

    .product-image {
        height: 250px;
        overflow: hidden;
        position: relative;
        background: var(--bg-darker);
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-info {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-title {
        color: var(--text-light);
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        text-decoration: none;
        transition: color 0.3s;
    }

    .product-title:hover {
        color: var(--secondary-purple);
    }

    .product-price {
        font-size: 24px;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Sort Section */
    .sort-section {
        background-color: var(--bg-card);
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 30px;
        border: 1px solid var(--border-dark);
    }

    /* Filters Column Fix */
    .filters-column {
        background-color: var(--bg-card);
        padding: 0;
        border-radius: 15px;
        border: 1px solid var(--border-dark);
        overflow: hidden;
    }

    /* Products Grid Container */
    .products-grid {
        width: 100%;
    }

    /* No Products State */
    .no-products {
        background: var(--bg-card);
        border-radius: 15px;
        padding: 60px 30px;
        border: 1px solid var(--border-dark);
        text-align: center;
    }

    /* ========================================= */
    /* CRITICAL: Bootstrap Grid Override Fixes   */
    /* ========================================= */
    
    /* Ensure proper container behavior */
    .container {
        width: 100%;
        padding-right: var(--bs-gutter-x, 0.75rem);
        padding-left: var(--bs-gutter-x, 0.75rem);
        margin-right: auto;
        margin-left: auto;
    }

    /* Fix margin utilities */
    .my-5 {
        margin-top: 3rem !important;
        margin-bottom: 3rem !important;
    }

    .mt-5 {
        margin-top: 3rem !important;
    }

    .mb-3 {
        margin-bottom: 1rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    /* Fix padding utilities */
    .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
    }

    /* Text alignment */
    .text-center {
        text-align: center !important;
    }

    /* Display utilities */
    .d-flex {
        display: flex !important;
    }

    .d-block {
        display: block !important;
    }

    /* Gap utilities */
    .gap-3 {
        gap: 1rem !important;
    }

    .gap-4 {
        gap: 1.5rem !important;
    }

    /* Additional responsive adjustments */
    @media (max-width: 991px) {
        .sort-section {
            margin-bottom: 20px;
        }
        
        .products-grid .col-lg-4 {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 767px) {
        .category-header {
            padding: 40px 0;
        }
        
        .category-header .category-icon {
            font-size: 48px;
        }
        
        .product-card {
            margin-bottom: 20px;
        }
    }
</style>

