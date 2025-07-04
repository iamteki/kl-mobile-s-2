@extends('layouts.app')

@section('title', 'Booking Details - KL Mobile DJ & Events')

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
                <!-- Booking Header -->
                <div class="booking-detail-header">
                    <div>
                        <a href="{{ route('account.bookings') }}" class="back-link">
                            <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                        </a>
                        <h1 class="page-title mt-3">Booking Details</h1>
                        <p class="booking-number">Booking #{{ $booking->booking_number }}</p>
                    </div>
                    <div class="header-actions">
                        @if($booking->payment_status == 'paid')
                            <a href="{{ route('account.booking.invoice', $booking) }}" class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Download Invoice
                            </a>
                        @endif
                        @if($daysUntilEvent > 0 && in_array($booking->booking_status, ['pending', 'confirmed']))
                            <div class="countdown-badge">
                                <i class="fas fa-clock"></i>
                                {{ $daysUntilEvent }} days until event
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Cards -->
                <div class="row status-cards mb-4">
                    <div class="col-md-3 col-6">
                        <div class="status-card">
                            <span class="status-label">Booking Status</span>
                            <span class="badge bg-{{ 
                                $booking->booking_status == 'completed' ? 'success' : 
                                ($booking->booking_status == 'confirmed' ? 'info' : 
                                ($booking->booking_status == 'cancelled' ? 'danger' : 'warning')) 
                            }}">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="status-card">
                            <span class="status-label">Payment Status</span>
                            <span class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="status-card">
                            <span class="status-label">Delivery Status</span>
                            <span class="badge bg-{{ 
                                $booking->delivery_status == 'delivered' ? 'success' : 
                                ($booking->delivery_status == 'scheduled' ? 'info' : 'secondary') 
                            }}">
                                {{ ucfirst($booking->delivery_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="status-card">
                            <span class="status-label">Total Amount</span>
                            <span class="amount">LKR {{ number_format($booking->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Event Information -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-calendar-alt me-2"></i>Event Information
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Event Type</span>
                            <span class="value">{{ ucfirst($booking->event_type) }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Event Date</span>
                            <span class="value">{{ $booking->event_date->format('l, d F Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Number of Guests</span>
                            <span class="value">{{ $booking->number_of_pax }} pax</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Venue</span>
                            <span class="value">{{ $booking->venue }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Setup Time</span>
                            <span class="value">{{ $booking->installation_time }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Event Time</span>
                            <span class="value">{{ $booking->event_start_time }} - {{ $booking->dismantle_time }}</span>
                        </div>
                    </div>
                    @if($booking->special_requests)
                        <div class="special-requests">
                            <h5>Special Requests</h5>
                            <p>{{ $booking->special_requests }}</p>
                        </div>
                    @endif
                </div>

                <!-- Items Booked -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-shopping-cart me-2"></i>Items Booked
                    </h3>
                    <div class="items-table-wrapper">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->items as $item)
                                    <tr>
                                        <td>
                                            <div class="item-info">
                                                <strong>{{ $item->item_name }}</strong>
                                                @if($item->variation_name)
                                                    <br><small class="text-muted">{{ $item->variation_name }}</small>
                                                @endif
                                                @if($item->item_type == 'product' && $item->rental_days > 1)
                                                    <br><small class="text-muted">{{ $item->rental_days }} days rental</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="item-type">{{ ucfirst($item->item_type) }}</span>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>LKR {{ number_format($item->unit_price, 2) }}</td>
                                        <td>LKR {{ number_format($item->total_price, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $item->status == 'delivered' ? 'success' : 
                                                ($item->status == 'confirmed' ? 'info' : 'secondary') 
                                            }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-truck me-2"></i>Delivery Information
                    </h3>
                    <div class="info-grid">
                        <div class="info-item full-width">
                            <span class="label">Delivery Address</span>
                            <span class="value">{{ $booking->delivery_address }}</span>
                        </div>
                        @if($booking->delivery_instructions)
                            <div class="info-item full-width">
                                <span class="label">Delivery Instructions</span>
                                <span class="value">{{ $booking->delivery_instructions }}</span>
                            </div>
                        @endif
                        <div class="info-item">
                            <span class="label">Setup Time</span>
                            <span class="value">{{ $booking->installation_time }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Pickup Time</span>
                            <span class="value">{{ $booking->dismantle_time }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-receipt me-2"></i>Payment Summary
                    </h3>
                    <div class="payment-summary">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>LKR {{ number_format($booking->subtotal, 2) }}</span>
                        </div>
                        @if($booking->discount_amount > 0)
                            <div class="summary-row discount">
                                <span>Discount 
                                    @if($booking->coupon_code)
                                        ({{ $booking->coupon_code }})
                                    @endif
                                </span>
                                <span>-LKR {{ number_format($booking->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="summary-row">
                            <span>Delivery Charge</span>
                            <span>LKR {{ number_format($booking->delivery_charge, 2) }}</span>
                        </div>
                        @if($booking->tax_amount > 0)
                            <div class="summary-row">
                                <span>Tax</span>
                                <span>LKR {{ number_format($booking->tax_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="summary-row total">
                            <span>Total Paid</span>
                            <span>LKR {{ number_format($booking->total, 2) }}</span>
                        </div>
                    </div>
                    
                    @if($booking->payment_status == 'paid')
                        <div class="payment-info">
                            <p><strong>Payment Method:</strong> {{ ucfirst($booking->payment_method) }}</p>
                            <p><strong>Paid On:</strong> {{ $booking->paid_at->format('d M Y, h:i A') }}</p>
                            <p><strong>Transaction ID:</strong> {{ Str::limit($booking->stripe_payment_intent_id, 30) }}</p>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if(in_array($booking->booking_status, ['pending', 'confirmed']) && $daysUntilEvent > 2)
                        <button type="button" 
                                class="btn btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#cancelModal">
                            <i class="fas fa-times me-2"></i>Cancel Booking
                        </button>
                    @endif
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                        <i class="fas fa-headset me-2"></i>Contact Support
                    </a>
                    <a href="{{ route('account.bookings') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
@if(in_array($booking->booking_status, ['pending', 'confirmed']) && $daysUntilEvent > 2)
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('account.booking.cancel', $booking) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Cancellations must be made at least 48 hours before the event date.
                        </div>
                        <p>Are you sure you want to cancel this booking?</p>
                        <div class="booking-info">
                            <p><strong>Booking:</strong> #{{ $booking->booking_number }}</p>
                            <p><strong>Event Date:</strong> {{ $booking->event_date->format('d M Y') }}</p>
                            <p><strong>Total Amount:</strong> LKR {{ number_format($booking->total, 2) }}</p>
                        </div>
                        <div class="form-group mt-3">
                            <label>Reason for cancellation *</label>
                            <textarea name="cancellation_reason" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Please tell us why you're cancelling..."
                                      required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Keep Booking
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
    .booking-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-dark);
    }

    .back-link {
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.875rem;
    }

    .back-link:hover {
        color: var(--primary-purple);
    }

    .page-title {
        color: var(--primary-purple);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .booking-number {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .countdown-badge {
        background: rgba(147, 51, 234, 0.1);
        color: var(--primary-purple);
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Status Cards */
    .status-cards {
        margin-bottom: 30px;
    }

    .status-card {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        height: 100%;
    }

    .status-label {
        display: block;
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 10px;
    }

    .status-card .badge {
        font-size: 0.875rem;
        padding: 6px 12px;
    }

    .status-card .amount {
        color: var(--primary-purple);
        font-size: 1.25rem;
        font-weight: 700;
    }

    /* Detail Sections */
    .detail-section {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
    }

    .section-title {
        color: var(--text-primary);
        font-size: 1.25rem;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-dark);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-item .label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 5px;
    }

    .info-item .value {
        color: var(--text-primary);
        font-weight: 500;
    }

    .special-requests {
        margin-top: 20px;
        padding: 15px;
        background: var(--bg-dark);
        border-radius: 10px;
    }

    .special-requests h5 {
        color: var(--text-primary);
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .special-requests p {
        color: var(--text-secondary);
        margin: 0;
    }

    /* Items Table */
    .items-table-wrapper {
        overflow-x: auto;
    }

    .items-table {
        width: 100%;
        color: var(--text-primary);
    }

    .items-table th {
        background: var(--bg-dark);
        color: var(--primary-purple);
        padding: 12px;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
    }

    .items-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border-dark);
    }

    .item-info strong {
        display: block;
        margin-bottom: 3px;
    }

    .item-type {
        background: rgba(147, 51, 234, 0.1);
        color: var(--primary-purple);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Payment Summary */
    .payment-summary {
        background: var(--bg-dark);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        color: var(--text-primary);
    }

    .summary-row.discount {
        color: var(--success);
    }

    .summary-row.total {
        border-top: 2px solid var(--border-dark);
        margin-top: 10px;
        padding-top: 15px;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-purple);
    }

    .payment-info {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .payment-info p {
        margin-bottom: 5px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid var(--border-dark);
    }

    /* Modal */
    .modal-content {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
    }

    .modal-header,
    .modal-footer {
        border-color: var(--border-dark);
    }

    .booking-info {
        background: var(--bg-dark);
        padding: 15px;
        border-radius: 8px;
        margin: 15px 0;
    }

    .booking-info p {
        margin-bottom: 5px;
        color: var(--text-primary);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .booking-detail-header {
            flex-direction: column;
            gap: 20px;
        }
        
        .header-actions {
            flex-direction: column;
            width: 100%;
        }
        
        .header-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .status-cards {
            margin-bottom: 20px;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .items-table {
            font-size: 0.875rem;
        }
        
        .items-table th,
        .items-table td {
            padding: 8px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
    }
</style>
@endpush
@endsection