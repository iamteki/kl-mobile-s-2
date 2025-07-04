@extends('layouts.app')

@section('title', 'Booking Confirmed - KL Mobile DJ & Events')

@section('content')
<div class="confirmation-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div class="confirmation-header text-center mb-5">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="confirmation-title">Booking Confirmed!</h1>
                    <p class="confirmation-subtitle">
                        Thank you for your booking. We've sent a confirmation email to <strong>{{ $booking->customer_email }}</strong>
                    </p>
                    <div class="booking-number">
                        <span>Booking Number:</span>
                        <strong>{{ $booking->booking_number }}</strong>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="confirmation-details">
                    <h3 class="section-title">Booking Details</h3>
                    
                    <!-- Event Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-calendar-alt me-2"></i>Event Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="label">Event Date:</span>
                                <span class="value">{{ \Carbon\Carbon::parse($booking->event_date)->format('l, d F Y') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Event Type:</span>
                                <span class="value">{{ ucfirst($booking->event_type) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Venue:</span>
                                <span class="value">{{ $booking->venue }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Number of Guests:</span>
                                <span class="value">{{ $booking->number_of_pax }} pax</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Setup Time:</span>
                                <span class="value">{{ $booking->installation_time }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Event Time:</span>
                                <span class="value">{{ $booking->event_start_time }} - {{ $booking->dismantle_time }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Items Booked -->
                    <div class="detail-section">
                        <h4><i class="fas fa-shopping-cart me-2"></i>Items Booked</h4>
                        <div class="items-table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->items as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->item_name }}</strong>
                                                @if($item->item_type == 'product' && $item->rental_days > 1)
                                                    <br><small class="text-muted">{{ $item->rental_days }} days rental</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>LKR {{ number_format($item->unit_price, 2) }}</td>
                                            <td>LKR {{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Subtotal:</td>
                                        <td>LKR {{ number_format($booking->subtotal, 2) }}</td>
                                    </tr>
                                    @if($booking->discount_amount > 0)
                                        <tr>
                                            <td colspan="3" class="text-end">Discount:</td>
                                            <td class="text-success">-LKR {{ number_format($booking->discount_amount, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="3" class="text-end">Delivery Charge:</td>
                                        <td>LKR {{ number_format($booking->delivery_charge, 2) }}</td>
                                    </tr>
                                    <tr class="total-row">
                                        <td colspan="3" class="text-end"><strong>Total Paid:</strong></td>
                                        <td><strong>LKR {{ number_format($booking->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-truck me-2"></i>Delivery Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item full-width">
                                <span class="label">Delivery Address:</span>
                                <span class="value">{{ $booking->delivery_address }}</span>
                            </div>
                            @if($booking->delivery_instructions)
                                <div class="detail-item full-width">
                                    <span class="label">Special Instructions:</span>
                                    <span class="value">{{ $booking->delivery_instructions }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="detail-section">
                        <h4><i class="fas fa-credit-card me-2"></i>Payment Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="label">Payment Method:</span>
                                <span class="value">{{ ucfirst($booking->payment_method) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Payment Status:</span>
                                <span class="value"><span class="badge bg-success">Paid</span></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Transaction ID:</span>
                                <span class="value">{{ Str::limit($booking->stripe_payment_intent_id, 20) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Paid On:</span>
                                <span class="value">{{ $booking->paid_at->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="next-steps">
                    <h3 class="section-title">What Happens Next?</h3>
                    <div class="steps-timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="timeline-content">
                                <h5>Confirmation Email</h5>
                                <p>You'll receive a detailed confirmation email with your booking information and invoice.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="timeline-content">
                                <h5>Pre-Event Contact</h5>
                                <p>Our team will contact you 24-48 hours before your event to confirm all details.</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <h5>Delivery & Setup</h5>
                                <p>We'll arrive at the scheduled time to deliver and set up all equipment.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="confirmation-actions">
                    <a href="{{ route('account.booking.details', $booking) }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>View Booking Details
                    </a>
                    <a href="{{ route('account.booking.invoice', $booking) }}" class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>Download Invoice
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>

                <!-- Contact Support -->
                <div class="support-section">
                    <p>Need help or have questions about your booking?</p>
                    <div class="contact-options">
                        <a href="tel:+94771234567" class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+94 77 123 4567</span>
                        </a>
                        <a href="mailto:support@klmobile.lk" class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>support@klmobile.lk</span>
                        </a>
                        <a href="https://wa.me/94771234567" class="contact-item">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .confirmation-wrapper {
        min-height: calc(100vh - 200px);
        padding: 60px 0;
        background: var(--bg-dark);
    }

    /* Success Header */
    .confirmation-header {
        margin-bottom: 40px;
    }

    .success-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        background: var(--success);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleIn 0.5s ease-out;
    }

    .success-icon i {
        font-size: 50px;
        color: white;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .confirmation-title {
        color: var(--primary-purple);
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .confirmation-subtitle {
        color: var(--text-secondary);
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    .booking-number {
        background: var(--bg-card);
        border: 2px solid var(--primary-purple);
        border-radius: 10px;
        padding: 15px 30px;
        display: inline-block;
    }

    .booking-number span {
        color: var(--text-secondary);
        margin-right: 10px;
    }

    .booking-number strong {
        color: var(--primary-purple);
        font-size: 1.2rem;
        letter-spacing: 1px;
    }

    /* Details Section */
    .confirmation-details {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .section-title {
        color: var(--primary-purple);
        font-size: 1.5rem;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-dark);
    }

    .detail-section {
        margin-bottom: 30px;
    }

    .detail-section:last-child {
        margin-bottom: 0;
    }

    .detail-section h4 {
        color: var(--text-primary);
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-item.full-width {
        grid-column: 1 / -1;
    }

    .detail-item .label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-bottom: 5px;
    }

    .detail-item .value {
        color: var(--text-primary);
        font-weight: 500;
    }

    /* Items Table */
    .items-table {
        overflow-x: auto;
    }

    .items-table .table {
        color: var(--text-primary);
        margin-bottom: 0;
    }

    .items-table th {
        background: var(--bg-dark);
        color: var(--primary-purple);
        border-color: var(--border-dark);
        padding: 12px;
        font-weight: 600;
    }

    .items-table td {
        border-color: var(--border-dark);
        padding: 12px;
    }

    .items-table tfoot tr {
        border-top: 2px solid var(--border-dark);
    }

    .items-table .total-row td {
        font-size: 1.2rem;
        padding-top: 15px;
    }

    /* Next Steps */
    .next-steps {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .steps-timeline {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .timeline-item {
        display: flex;
        gap: 20px;
    }

    .timeline-icon {
        width: 50px;
        height: 50px;
        background: rgba(147, 51, 234, 0.1);
        border: 2px solid var(--primary-purple);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .timeline-icon i {
        color: var(--primary-purple);
        font-size: 20px;
    }

    .timeline-content h5 {
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .timeline-content p {
        color: var(--text-secondary);
        margin: 0;
    }

    /* Action Buttons */
    .confirmation-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    /* Support Section */
    .support-section {
        text-align: center;
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 30px;
    }

    .support-section p {
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    .contact-options {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--primary-purple);
        text-decoration: none;
        padding: 10px 20px;
        background: rgba(147, 51, 234, 0.1);
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .contact-item:hover {
        background: var(--primary-purple);
        color: white;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .confirmation-title {
            font-size: 2rem;
        }
        
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .items-table {
            font-size: 0.875rem;
        }
        
        .confirmation-actions {
            flex-direction: column;
        }
        
        .confirmation-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Print invoice functionality
    function printInvoice() {
        window.print();
    }
    
    // Confetti animation (optional fun effect)
    if (typeof confetti !== 'undefined') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
</script>
@endpush
@endsection