<div class="account-sidebar">
    <div class="user-info">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <h5>{{ auth()->user()->name }}</h5>
        <p>{{ auth()->user()->email }}</p>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="{{ route('account.dashboard') }}" 
                   class="{{ request()->routeIs('account.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('account.bookings') }}" 
                   class="{{ request()->routeIs('account.bookings*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>My Bookings</span>
                    @if(isset($stats) && $stats['upcoming_events'] > 0)
                        <span class="badge">{{ $stats['upcoming_events'] }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('account.profile') }}" 
                   class="{{ request()->routeIs('account.profile*') ? 'active' : '' }}">
                    <i class="fas fa-user-edit"></i>
                    <span>Profile Settings</span>
                </a>
            </li>
            <li>
                <a href="{{ route('account.password') }}" 
                   class="{{ request()->routeIs('account.password') ? 'active' : '' }}">
                    <i class="fas fa-lock"></i>
                    <span>Change Password</span>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="{{ route('categories.index') }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>New Booking</span>
                </a>
            </li>
            <li>
                <a href="{{ route('contact') }}">
                    <i class="fas fa-headset"></i>
                    <span>Support</span>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </form>
            </li>
        </ul>
    </nav>
</div>

<style>
    .account-sidebar {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        position: sticky;
        top: 20px;
    }

    .user-info {
        text-align: center;
        padding-bottom: 25px;
        border-bottom: 1px solid var(--border-dark);
        margin-bottom: 25px;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
        background: rgba(147, 51, 234, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-avatar i {
        font-size: 40px;
        color: var(--primary-purple);
    }

    .user-info h5 {
        color: var(--text-primary);
        margin-bottom: 5px;
        font-weight: 600;
    }

    .user-info p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.875rem;
    }

    .sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav li {
        margin-bottom: 5px;
    }

    .sidebar-nav li.divider {
        height: 1px;
        background: var(--border-dark);
        margin: 15px 0;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s ease;
        position: relative;
    }

    .sidebar-nav a:hover {
        background: rgba(147, 51, 234, 0.1);
        color: var(--primary-purple);
    }

    .sidebar-nav a.active {
        background: var(--primary-purple);
        color: white;
    }

    .sidebar-nav a i {
        width: 20px;
        text-align: center;
    }

    .sidebar-nav a span {
        flex: 1;
    }

    .sidebar-nav .badge {
        background: var(--danger);
        color: white;
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 10px;
        position: absolute;
        right: 15px;
    }

    .sidebar-nav a.active .badge {
        background: white;
        color: var(--primary-purple);
    }

    @media (max-width: 991px) {
        .account-sidebar {
            position: static;
            margin-bottom: 30px;
        }
    }

    @media (max-width: 576px) {
        .sidebar-nav a {
            padding: 10px 12px;
            font-size: 0.875rem;
        }
    }
</style>