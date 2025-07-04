@extends('layouts.app')

@section('title', 'My Account - KL Mobile DJ & Events')

@section('content')
<div class="account-wrapper">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('frontend.account.partials.sidebar')
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Welcome Section -->
                <div class="welcome-section mb-4">
                    <h1 class="dashboard-title">Welcome back, {{ $user->name }}!</h1>
                    <p class="dashboard-subtitle">Here's what's happening with your events</p>
                </div>

                <!-- Statistics Cards -->
                <div class="row stats-row mb-5">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $stats['total_bookings'] }}</h3>
                                <p>Total Bookings</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon upcoming">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $stats['upcoming_events'] }}</h3>
                                <p>Upcoming Events</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon completed">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $stats['completed_events'] }}</h3>
                                <p>Completed</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon spent">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ number_format($stats['total_spent']) }}</h3>
                                <p>Total Spent (LKR)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                @if($upcomingEvents->isNotEmpty())
                    <div class="dashboard-section">
                        <div class="section-header">
                            <h2><i class="fas fa-calendar-day me-2"></i>Upcoming Events</h2>
                            <a href="{{ route('account.bookings') }}?status=upcoming" class="view-all">View All</a>
                        </div>
                        <div class="upcoming-events">
                            @foreach($upcomingEvents as $event)
                                <div class="event-card">
                                    <div class="event-date">
                                        <div class="date-day">{{ $event->event_date->format('d') }}</div>
                                        <div class="date-month">{{ $event->event_date->format('M') }}</div>
                                    </div>
                                    <div class="event-details">
                                        <h4>{{ ucfirst($event->event_type) }} Event</h4>
                                        <p class="event-venue">
                                            <i class="fas fa-map-marker-alt me-2"></i>{{ Str::limit($event->venue, 50) }}
                                        </p>
                                        <p class="event-time">
                                            <i class="fas fa-clock me-2"></i>{{ $event->event_start_time }} - {{ $event->dismantle_time }}
                                        </p>
                                    </div>
                                    <div class="event-status">
                                        <span class="badge bg-{{ $event->booking_status == 'confirmed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($event->booking_status) }}
                                        </span>
                                        <a href="{{ route('account.booking.details', $event) }}" class="btn btn-sm btn-outline-primary mt-2">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Bookings -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2><i class="fas fa-history me-2"></i>Recent Bookings</h2>
                        <a href="{{ route('account.bookings') }}" class="view-all">View All</a>
                    </div>
                    
                    @if($recentBookings->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h4>No Bookings Yet</h4>
                            <p>Start planning your event by browsing our equipment and services.</p>
                            <a href="{{ route('categories.index') }}" class="btn btn-primary">Browse Equipment</a>
                        </div>
                    @else
                        <div class="bookings-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Booking #</th>
                                        <th>Event Date</th>
                                        <th>Type</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <strong>{{ $booking->booking_number }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $booking->created_at->format('d M Y') }}</small>
                                            </td>
                                            <td>{{ $booking->event_date->format('d M Y') }}</td>
                                            <td>{{ ucfirst($booking->event_type) }}</td>
                                            <td>{{ $booking->items->count() }} items</td>
                                            <td>LKR {{ number_format($booking->total, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $booking->booking_status == 'completed' ? 'success' : 
                                                    ($booking->booking_status == 'confirmed' ? 'info' : 
                                                    ($booking->booking_status == 'cancelled' ? 'danger' : 'warning')) 
                                                }}">
                                                    {{ ucfirst($booking->booking_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('account.booking.details', $booking) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions mt-5">
                    <h3>Quick Actions</h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('categories.index') }}" class="action-card">
                                <i class="fas fa-plus-circle"></i>
                                <h5>New Booking</h5>
                                <p>Browse equipment and create a new booking</p>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('account.profile') }}" class="action-card">
                                <i class="fas fa-user-edit"></i>
                                <h5>Update Profile</h5>
                                <p>Keep your contact information up to date</p>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('contact') }}" class="action-card">
                                <i class="fas fa-headset"></i>
                                <h5>Get Support</h5>
                                <p>Contact our team for assistance</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .account-wrapper {
        min-height: calc(100vh - 200px);
        padding: 40px 0;
        background: var(--bg-dark);
    }

    /* Welcome Section */
    .welcome-section {
        margin-bottom: 30px;
    }

    .dashboard-title {
        color: var(--primary-purple);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .dashboard-subtitle {
        color: var(--text-secondary);
        font-size: 1.1rem;
    }

    /* Statistics Cards */
    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-purple);
        box-shadow: 0 5px 20px rgba(147, 51, 234, 0.2);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(147, 51, 234, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .stat-icon i {
        font-size: 24px;
        color: var(--primary-purple);
    }

    .stat-icon.upcoming {
        background: rgba(59, 130, 246, 0.1);
    }

    .stat-icon.upcoming i {
        color: #3B82F6;
    }

    .stat-icon.completed {
        background: rgba(34, 197, 94, 0.1);
    }

    .stat-icon.completed i {
        color: var(--success);
    }

    .stat-icon.spent {
        background: rgba(245, 158, 11, 0.1);
    }

    .stat-icon.spent i {
        color: var(--warning);
    }

    .stat-content h3 {
        color: var(--text-primary);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-content p {
        color: var(--text-secondary);
        margin: 0;
    }

    /* Dashboard Sections */
    .dashboard-section {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-dark);
    }

    .section-header h2 {
        color: var(--text-primary);
        font-size: 1.5rem;
        margin: 0;
    }

    .view-all {
        color: var(--primary-purple);
        text-decoration: none;
        font-weight: 500;
    }

    .view-all:hover {
        color: var(--secondary-purple);
    }

    /* Upcoming Events */
    .upcoming-events {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .event-card {
        display: flex;
        gap: 20px;
        padding: 20px;
        background: var(--bg-dark);
        border-radius: 10px;
        border: 1px solid var(--border-dark);
        transition: all 0.3s ease;
    }

    .event-card:hover {
        border-color: var(--primary-purple);
    }

    .event-date {
        background: var(--primary-purple);
        color: white;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        min-width: 80px;
    }

    .date-day {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .date-month {
        font-size: 0.875rem;
        text-transform: uppercase;
    }

    .event-details {
        flex: 1;
    }

    .event-details h4 {
        color: var(--text-primary);
        margin-bottom: 10px;
    }

    .event-details p {
        color: var(--text-secondary);
        margin-bottom: 5px;
        font-size: 0.875rem;
    }

    .event-status {
        text-align: right;
    }

    /* Bookings Table */
    .bookings-table {
        overflow-x: auto;
    }

    .bookings-table .table {
        color: var(--text-primary);
        margin-bottom: 0;
    }

    .bookings-table th {
        background: var(--bg-dark);
        color: var(--primary-purple);
        border-color: var(--border-dark);
        font-weight: 600;
        white-space: nowrap;
    }

    .bookings-table td {
        border-color: var(--border-dark);
        vertical-align: middle;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-secondary);
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h4 {
        color: var(--text-primary);
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    /* Quick Actions */
    .quick-actions {
        margin-top: 40px;
    }

    .quick-actions h3 {
        color: var(--text-primary);
        margin-bottom: 20px;
    }

    .action-card {
        display: block;
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s ease;
        height: 100%;
    }

    .action-card:hover {
        border-color: var(--primary-purple);
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(147, 51, 234, 0.2);
    }

    .action-card i {
        font-size: 3rem;
        color: var(--primary-purple);
        margin-bottom: 15px;
    }

    .action-card h5 {
        color: var(--text-primary);
        margin-bottom: 10px;
    }

    .action-card p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.875rem;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .stats-row {
            margin-bottom: 30px;
        }
        
        .event-card {
            flex-direction: column;
            text-align: center;
        }
        
        .event-status {
            text-align: center;
            margin-top: 15px;
        }
    }

    @media (max-width: 576px) {
        .dashboard-title {
            font-size: 1.5rem;
        }
        
        .stat-card {
            padding: 20px 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
        }
        
        .stat-content h3 {
            font-size: 1.5rem;
        }
        
        .bookings-table {
            font-size: 0.875rem;
        }
    }
</style>
@endpush
@endsection