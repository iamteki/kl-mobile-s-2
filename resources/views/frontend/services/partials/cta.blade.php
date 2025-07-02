<!-- Call to Action Section -->
<section class="services-cta py-5">
    <div class="container">
        <div class="cta-card text-center">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold text-white mb-4">
                        Can't Find What You're Looking For?
                    </h2>
                    <p class="lead text-white-50 mb-5">
                        We have a network of professional service providers. Contact us with your specific requirements and we'll find the perfect match for your event.
                    </p>
                    <div class="cta-buttons">
                        <a href="{{ route('contact') }}" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-envelope me-2"></i>
                            Contact Us
                        </a>
                        <a href="tel:+94771234567" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-phone me-2"></i>
                            Call +94 77 123 4567
                        </a>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="trust-indicators mt-5">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="trust-item">
                                    <i class="fas fa-users fa-2x text-primary mb-3"></i>
                                    <h5 class="text-white">500+ Service Providers</h5>
                                    <p class="text-white-50 small mb-0">Vetted professionals</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="trust-item">
                                    <i class="fas fa-calendar-check fa-2x text-success mb-3"></i>
                                    <h5 class="text-white">1000+ Events</h5>
                                    <p class="text-white-50 small mb-0">Successfully completed</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="trust-item">
                                    <i class="fas fa-star fa-2x text-warning mb-3"></i>
                                    <h5 class="text-white">4.8/5 Rating</h5>
                                    <p class="text-white-50 small mb-0">Customer satisfaction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.services-cta {
    background-color: var(--bg-darker);
    position: relative;
    overflow: hidden;
}

.cta-card {
    background: linear-gradient(135deg, var(--bg-card) 0%, rgba(147, 51, 234, 0.1) 100%);
    padding: 60px 40px;
    border-radius: 30px;
    border: 1px solid var(--border-dark);
    position: relative;
    overflow: hidden;
}

.cta-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(147, 51, 234, 0.1) 0%, transparent 70%);
    animation: pulse 4s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
}

.cta-buttons .btn {
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s;
}

.cta-buttons .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(147, 51, 234, 0.3);
}

.trust-indicators {
    position: relative;
    z-index: 1;
}

.trust-item {
    padding: 20px;
    transition: transform 0.3s;
}

.trust-item:hover {
    transform: translateY(-5px);
}

.trust-item i {
    opacity: 0.8;
}
</style>