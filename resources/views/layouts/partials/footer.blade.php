<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h4 class="text-white mb-3">KL Mobile</h4>
                <p class="text-muted">Your trusted partner for event equipment rental and professional services in Kuala Lumpur.</p>
                <div class="social-icons mt-3">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-white mb-3">Equipment</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('category.show', 'sound-equipment') }}">Sound Systems</a></li>
                    <li><a href="{{ route('category.show', 'lighting') }}">Lighting</a></li>
                    <li><a href="{{ route('category.show', 'led-screens') }}">LED Screens</a></li>
                    <li><a href="{{ route('category.show', 'dj-equipment') }}">DJ Equipment</a></li>
                    <li><a href="{{ route('category.show', 'staging') }}">Staging</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-white mb-3">Services</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('services.index') }}#djs">Professional DJs</a></li>
                    <li><a href="{{ route('services.index') }}#emcees">Event Emcees</a></li>
                    <li><a href="{{ route('services.index') }}#bands">Live Bands</a></li>
                    <li><a href="{{ route('services.index') }}#technicians">Technicians</a></li>
                    <li><a href="{{ route('services.index') }}#planning">Event Planning</a></li>
                </ul>
            </div>
            <div class="col-lg-4 mb-4">
                <h5 class="text-white mb-3">Contact Info</h5>
                <p class="text-muted">
                    <i class="fas fa-map-marker-alt me-2" style="color: var(--primary-purple);"></i> 
                    Level 15, Menara KL, Jalan Sultan Ismail, 50250 KL
                </p>
                <p class="text-muted">
                    <i class="fas fa-phone me-2" style="color: var(--primary-purple);"></i> 
                    +60 3-1234 5678
                </p>
                <p class="text-muted">
                    <i class="fas fa-envelope me-2" style="color: var(--primary-purple);"></i> 
                    info@klmobile.com
                </p>
            </div>
        </div>
        <hr class="my-4" style="border-color: var(--border-dark);">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0 text-muted">&copy; {{ date('Y') }} KL Mobile. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('terms') }}" class="text-muted text-decoration-none me-3">Terms & Conditions</a>
                <a href="{{ route('privacy') }}" class="text-muted text-decoration-none">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>