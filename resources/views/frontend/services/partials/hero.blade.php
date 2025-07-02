<!-- Hero Section -->
<section class="services-hero position-relative">
    <div class="hero-bg-overlay"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-3 fw-bold text-white mb-4">
                    Professional Event Services
                </h1>
                <p class="lead text-white-50 mb-5">
                    From talented DJs to expert photographers, find the perfect professionals to make your event unforgettable
                </p>
                <div class="hero-search">
                    <form class="search-form">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   placeholder="Search for services (e.g., DJ, Photographer, Emcee)">
                            <button class="btn btn-primary btn-lg" type="submit">
                                <i class="fas fa-search"></i>
                                Search
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Popular Searches -->
                <div class="popular-searches mt-4">
                    <span class="text-white-50 me-3">Popular:</span>
                    <a href="{{ route('services.index', ['category' => 'entertainment']) }}" class="badge bg-secondary me-2">Professional DJs</a>
                    <a href="{{ route('services.index', ['category' => 'media-production']) }}" class="badge bg-secondary me-2">Videographers</a>
                    <a href="{{ route('services.index', ['category' => 'entertainment']) }}" class="badge bg-secondary me-2">Live Bands</a>
                    <a href="{{ route('services.index', ['category' => 'event-staff']) }}" class="badge bg-secondary">Event Coordinators</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.services-hero {
    background-image: url('https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=1920&h=1080&fit=crop');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 50vh;
    display: flex;
    align-items: center;
    position: relative;
}

.hero-bg-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(147,51,234,0.3) 100%);
    z-index: 0;
}

.min-vh-50 {
    min-height: 50vh;
}

.hero-search {
    max-width: 600px;
    margin: 0 auto;
}

.search-form .form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    padding: 15px 20px;
    font-size: 16px;
}

.search-form .form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.search-form .form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: var(--primary-purple);
    color: white;
    box-shadow: 0 0 0 0.25rem rgba(147, 51, 234, 0.25);
}

.search-form .btn {
    padding: 15px 30px;
}

.popular-searches a {
    text-decoration: none;
    transition: all 0.3s;
}

.popular-searches a:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(147, 51, 234, 0.3);
}
</style>