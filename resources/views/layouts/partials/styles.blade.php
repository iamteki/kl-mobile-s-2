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
</style>