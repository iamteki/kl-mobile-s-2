<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">KL Mobile</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('equipment/*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">Equipment</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('category.show', 'sound-equipment') }}">Sound Systems</a></li>
                        <li><a class="dropdown-item" href="{{ route('category.show', 'lighting') }}">Lighting</a></li>
                        <li><a class="dropdown-item" href="{{ route('category.show', 'led-screens') }}">LED Screens</a></li>
                        <li><a class="dropdown-item" href="{{ route('category.show', 'dj-equipment') }}">DJ Equipment</a></li>
                        <li><a class="dropdown-item" href="{{ route('category.show', 'backdrops') }}">Backdrops</a></li>
                        <li><a class="dropdown-item" href="{{ route('category.show', 'tables-chairs') }}">Tables & Chairs</a></li>
                        <li><a class="dropdown-item" href="{{ route('category.show', 'tents-canopy') }}">Tents & Canopy</a></li>
                        <li><a class="dropdown-item" href="{{ route('category.show', 'photo-booths') }}">Photo Booths</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('services*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">Services</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('services.index') }}#djs">Professional DJs</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.index') }}#emcees">Event Emcees</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.index') }}#bands">Live Bands</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.index') }}#technical">Technical Crew</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.index') }}#photography">Photography & Video</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.index') }}#staff">Event Staff</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}">Packages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            <div class="d-flex">
                @auth
                    <a href="{{ route('account.dashboard') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-user"></i> Account
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                @endauth
                
                <!-- Cart Dropdown Component -->
                @livewire('cart-dropdown')
                
                <a href="{{ route('booking.quick') }}" class="btn btn-primary ms-2">Book Now</a>
            </div>
        </div>
    </div>
</nav>