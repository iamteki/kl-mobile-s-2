<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-fade-in-up">
                <h1 class="hero-title">
                    Rent <span>Event Equipment</span> & Book <span>Professional Services</span>
                </h1>
                <p class="lead mb-4">
                    Complete event solutions with instant booking and real-time availability. 
                    From sound systems to professional DJs - we've got everything covered.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-box me-2"></i>Browse Equipment
                    </a>
                    <a href="{{ route('packages.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-star me-2"></i>View Packages
                    </a>
                </div>
                
                <!-- Trust Indicators -->
                <div class="trust-indicators mt-5">
                    <div class="row g-4">
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="text-muted">Instant Booking</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-truck text-primary me-2"></i>
                                <span class="text-muted">Free Delivery in KL</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt text-warning me-2"></i>
                                <span class="text-muted">Equipment Insurance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="hero-image-wrapper position-relative">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&h=400&fit=crop" 
                         alt="Event Equipment" 
                         class="img-fluid rounded shadow animate-fade-in-up"
                         style="animation-delay: 0.2s;">
                    
                    <!-- Floating Stats -->
                    <div class="floating-stat position-absolute top-0 start-0 bg-white rounded-3 shadow p-3 m-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-calendar-check text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">1000+</div>
                                <div class="small text-muted">Events Served</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="floating-stat position-absolute bottom-0 end-0 bg-white rounded-3 shadow p-3 m-4">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-star text-success"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">4.8/5</div>
                                <div class="small text-muted">Customer Rating</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hero-image-wrapper {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.floating-stat {
    animation: fadeInScale 0.8s ease-out;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95) !important;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.stat-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.trust-indicators {
    opacity: 0.8;
}
</style>