<section class="how-it-works">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white">How It Works</h2>
            <p class="text-muted">Simple booking process with instant confirmation</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <div class="step-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h5 class="text-white">1. Browse & Select</h5>
                <p class="text-muted">Choose equipment or packages based on your event needs</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="step-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h5 class="text-white">2. Check Availability</h5>
                <p class="text-muted">Real-time availability for your event date</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="step-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h5 class="text-white">3. Book & Pay</h5>
                <p class="text-muted">Secure payment via Stripe with instant confirmation</p>
            </div>
            
            <div class="col-md-3 text-center">
                <div class="step-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h5 class="text-white">4. Delivery & Setup</h5>
                <p class="text-muted">We deliver and set up everything at your venue</p>
            </div>
        </div>
        
        <!-- Trust Badges -->
        <div class="trust-badges mt-5 pt-5 border-top border-dark">
            <div class="row g-4 align-items-center">
                <div class="col-md-3 col-6 text-center">
                    <div class="trust-badge">
                        <i class="fas fa-shield-alt fa-3x mb-3 text-primary"></i>
                        <h6 class="text-white">Insured Equipment</h6>
                        <p class="text-muted small mb-0">All rentals covered</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center">
                    <div class="trust-badge">
                        <i class="fas fa-clock fa-3x mb-3 text-success"></i>
                        <h6 class="text-white">24/7 Support</h6>
                        <p class="text-muted small mb-0">Always here to help</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center">
                    <div class="trust-badge">
                        <i class="fas fa-users fa-3x mb-3 text-warning"></i>
                        <h6 class="text-white">Expert Team</h6>
                        <p class="text-muted small mb-0">Professional technicians</p>
                    </div>
                </div>
                <div class="col-md-3 col-6 text-center">
                    <div class="trust-badge">
                        <i class="fas fa-award fa-3x mb-3 text-info"></i>
                        <h6 class="text-white">Best Price</h6>
                        <p class="text-muted small mb-0">Guaranteed value</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.step-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.2) 0%, rgba(124, 58, 237, 0.2) 100%);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.step-icon:hover {
    transform: scale(1.1);
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.3) 0%, rgba(124, 58, 237, 0.3) 100%);
}

.step-icon i {
    font-size: 32px;
    background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.trust-badge {
    transition: transform 0.3s;
}

.trust-badge:hover {
    transform: translateY(-5px);
}

.trust-badge i {
    opacity: 0.8;
}
</style>