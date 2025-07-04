@extends('layouts.app')

@section('title', 'My Bookings - KL Mobile DJ & Events')

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
                <div class="bookings-section">
                    <div class="section-header">
                        <h1 class="page-title">My Bookings</h1>
                        <a href="{{ route('categories.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>New Booking
                        </a>
                    </div>

                    <!-- Filters -->
                    <div class="filters-section">
                        <form method="GET" action="{{ route('account.bookings') }}" class="filters-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Status</label>
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="all">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>From Date</label>
                                    <input type="date" 
                                           name="from_date" 
                                           class="form-control" 
                                           value="{{ request('from_date') }}"
                                           onchange="this.form.submit()">
                                </div>
                                <div class="col-md-3">
                                    <label>To Date</label>
                                    <input type="date" 
                                           name="to_date" 
                                           class="form-control" 
                                           value="{{ request('to_date') }}"
                                           onchange="this.form.submit()">
                                </div>
                                <div class="col-md-3">
                                    <label>&nbsp;</label>
                                    <a href="{{ route('account.bookings') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-times me-2"></i>Clear Filters
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Bookings List -->
                    @if($bookings->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <h4>No Bookings Found</h4>
                            <p>{{ request()->has('status') || request()->has('from_date') ? 'Try adjusting your filters.' : 'Start planning your first event!' }}</p>
                            <a href="{{ route('categories.index') }}" class="btn btn-primary">Browse Equipment</a>
                        </div>
                    @else
                        <div class="bookings-list">
                            @foreach($bookings as $booking)
                                <div class="booking-card">
                                    <div class="booking-header">
                                        <div>
                                            <h4>{{ ucfirst($booking->event_type) }} Event</h4>
                                            <p class="booking-number">Booking #{{ $booking->booking_number }}</p>
                                        </div>
                                        <div class="booking-status">
                                            <span class="badge bg-{{ 
                                                $booking->booking_status == 'completed' ? 'success' : 
                                                ($booking->booking_status == 'confirmed' ? 'info' : 
                                                ($booking->booking_status == 'cancelled' ? 'danger' : 'warning')) 
                                            }}">
                                                {{ ucfirst($booking->booking_status) }}
                                            </span>
                                            <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="booking-details">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="detail-item">
                                                    <span class="label">Event Date</span>
                                                    <span class="value">{{ $booking->event_date->format('d M Y') }}</span>
                                                    <span class="sub-value">{{ $booking->event_date->format('l') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="detail-item">
                                                    <span class="label">Venue</span>
                                                    <span class="value">{{ Str::limit($booking->venue, 30) }}</span>
                                                    <span class="sub-value">{{ $booking->number_of_pax }} guests</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="detail-item">
                                                    <span class="label">Items</span>
                                                    <span class="value">{{ $booking->items->count() }} items</span>
                                                    <span class="sub-value">{{ $booking->event_start_time }} - {{ $booking->dismantle_time }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="detail-item">
                                                    <span class="label">Total Amount</span>
                                                    <span class="value">LKR {{ number_format($booking->total, 2) }}</span>
                                                    <span class="sub-value">Booked {{ $booking->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="booking-actions">
                                        <a href="{{ route('account.booking.details', $booking) }}" class="btn btn-primary">
                                            <i class="fas fa-eye me-2"></i>View Details
                                        </a>
                                        @if($booking->payment_status == 'paid')
                                            <a href="{{ route('account.booking.invoice', $booking) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-download me-2"></i>Invoice
                                            </a>
                                        @endif
                                        @if(in_array($booking->booking_status, ['pending', 'confirmed']) && $booking->event_date->diffInHours(now()) > 48)
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#cancelModal{{ $booking->id }}">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Cancel Modal -->
                                @if(in_array($booking->booking_status, ['pending', 'confirmed']) && $booking->event_date->diffInHours(now()) > 48)
                                    <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Cancel Booking</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('account.booking.cancel', $booking) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to cancel this booking?</p>
                                                        <p class="text-warning">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            Cancellations must be made at least 48 hours before the event.
                                                        </p>
                                                        <div class="form-group">
                                                            <label>Reason for cancellation *</label>
                                                            <textarea name="cancellation_reason" 
                                                                      class="form-control" 
                                                                      rows="3" 
                                                                      required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            Keep Booking
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">
                                                            Cancel Booking
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bookings-section {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 30px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-dark);
    }

    .page-title {
        color: var(--primary-purple);
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    /* Filters */
    .filters-section {
        background: var(--bg-dark);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .filters-form label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 5px;
    }

    .filters-form .form-control {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
    }

    /* Booking Cards */
    .booking-card {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .booking-card:hover {
        border-color: var(--primary-purple);
        box-shadow: 0 5px 20px rgba(147, 51, 234, 0.1);
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .booking-header h4 {
        color: var(--text-primary);
        margin-bottom: 5px;
    }

    .booking-number {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0;
    }

    .booking-status {
        display: flex;
        gap: 10px;
    }

    .booking-details {
        padding: 20px 0;
        border-top: 1px solid var(--border-dark);
        border-bottom: 1px solid var(--border-dark);
    }

    .detail-item {
        text-align: center;
    }

    .detail-item .label {
        display: block;
        color: var(--text-secondary);
        font-size: 0.75rem;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .detail-item .value {
        display: block;
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 3px;
    }

    .detail-item .sub-value {
        display: block;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .booking-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
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

    /* Modal Styles */
    .modal-content {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
    }

    .modal-header {
        border-bottom: 1px solid var(--border-dark);
    }

    .modal-footer {
        border-top: 1px solid var(--border-dark);
    }

    .modal-title {
        color: var(--text-primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .section-header {
            flex-direction: column;
            gap: 15px;
        }
        
        .booking-header {
            flex-direction: column;
            gap: 10px;
        }
        
        .booking-actions {
            flex-wrap: wrap;
        }
        
        .booking-actions .btn {
            flex: 1;
            min-width: 120px;
        }
    }
</style>
@endpush
@endsection