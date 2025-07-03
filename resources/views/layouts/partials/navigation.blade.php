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
                
                <!-- Equipment Dropdown (Dynamic) -->
                @if($navEquipmentCategories->count() > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('equipment/*') ? 'active' : '' }}" 
                           href="#" 
                           data-bs-toggle="dropdown">
                            Equipment
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('categories.index') }}">
                                    <i class="fas fa-th-large me-2"></i>All Categories
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @foreach($navEquipmentCategories as $category)
                                <li>
                                    <a class="dropdown-item" href="{{ route('category.show', $category->slug) }}">
                                        @if($category->icon)
                                            <i class="{{ $category->icon }} me-2" style="width: 20px;"></i>
                                        @endif
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                
                <!-- Services Dropdown (Dynamic) -->
                @if($navServiceCategories->count() > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('services*') ? 'active' : '' }}" 
                           href="#" 
                           data-bs-toggle="dropdown">
                            Services
                        </a>
                        <ul class="dropdown-menu dropdown-menu-services">
                            <li>
                                <a class="dropdown-item" href="{{ route('services.index') }}">
                                    <i class="fas fa-users me-2"></i>All Services
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            @foreach($navServiceParentCategories as $parentKey => $parentInfo)
                                @if(isset($navServiceCategories[$parentKey]) && $navServiceCategories[$parentKey]->count() > 0)
                                    <li class="dropdown-header">
                                        <i class="{{ $parentInfo['icon'] }} me-1"></i>
                                        {{ $parentInfo['name'] }}
                                    </li>
                                    
                                    @foreach($navServiceCategories[$parentKey] as $category)
                                        <li>
                                            <a class="dropdown-item ps-4" 
                                               href="{{ route('services.category', $category->slug) }}">
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }} me-2" style="width: 20px;"></i>
                                                @endif
                                                {{ $category->name }}
                                                <span class="badge bg-secondary ms-1">{{ $category->active_providers_count }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                    
                                    @if(!$loop->last)
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
                
                <!-- Packages (Conditional) -->
                @if($navHasPackages)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('packages*') ? 'active' : '' }}" 
                           href="{{ route('packages.index') }}">
                            Packages
                        </a>
                    </li>
                @endif
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center">
                @auth
                    <a href="{{ route('account.dashboard') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-user"></i> 
                        <span class="d-none d-lg-inline">Account</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                @endauth
                
                <!-- Cart Dropdown Component -->
                @livewire('cart-dropdown')
                
                <a href="{{ route('booking.quick') }}" class="btn btn-primary ms-2 d-none d-md-inline-block">
                    <i class="fas fa-calendar-check me-1"></i>Book Now
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
/* Enhanced Dropdown Styles */
.dropdown-menu {
    min-width: 250px;
    max-height: 70vh;
    overflow-y: auto;
    padding: 10px 0;
    scrollbar-width: thin;
    scrollbar-color: var(--primary-purple) var(--bg-dark);
}

.dropdown-menu::-webkit-scrollbar {
    width: 6px;
}

.dropdown-menu::-webkit-scrollbar-track {
    background: var(--bg-dark);
}

.dropdown-menu::-webkit-scrollbar-thumb {
    background: var(--primary-purple);
    border-radius: 3px;
}

.dropdown-menu-services {
    min-width: 300px;
}

.dropdown-header {
    color: var(--primary-purple);
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 8px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.dropdown-divider {
    border-color: var(--border-dark);
    margin: 5px 0;
}

.dropdown-item {
    padding: 8px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.dropdown-item i {
    color: var(--primary-purple);
    opacity: 0.7;
}

.dropdown-item:hover i {
    opacity: 1;
}

.dropdown-item .badge {
    font-size: 10px;
    padding: 2px 6px;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    .navbar-nav {
        padding: 20px 0;
    }
    
    .dropdown-menu {
        border: none;
        background-color: rgba(20, 20, 20, 0.95);
        max-height: none;
    }
    
    .navbar-collapse {
        background-color: var(--bg-card);
        margin-top: 15px;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid var(--border-dark);
    }
}
</style>