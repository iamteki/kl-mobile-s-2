@extends('layouts.app')

@section('title', 'Event Details - Checkout')

@section('content')
<div class="checkout-wrapper">
    <div class="container">
        <!-- Checkout Progress -->
        <div class="checkout-progress mb-5">
            <div class="progress-step active">
                <span class="step-number">1</span>
                <span class="step-title">Event Details</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <span class="step-number">2</span>
                <span class="step-title">Customer Info</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <span class="step-number">3</span>
                <span class="step-title">Payment</span>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step">
                <span class="step-number">4</span>
                <span class="step-title">Confirmation</span>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="checkout-section">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-alt me-2"></i>Event Details
                    </h2>
                    
                    <form method="POST" action="{{ route('checkout.event-details') }}" class="checkout-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="event_date" class="form-label">Event Date *</label>
                                    <input type="date" 
                                           class="form-control @error('event_date') is-invalid @enderror" 
                                           id="event_date" 
                                           name="event_date" 
                                           value="{{ old('event_date', $eventDetails['event_date'] ?? '') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           required>
                                    @error('event_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text">Minimum booking is 1 day in advance</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="event_type" class="form-label">Event Type *</label>
                                    <select class="form-control @error('event_type') is-invalid @enderror" 
                                            id="event_type" 
                                            name="event_type" 
                                            required>
                                        <option value="">Select Event Type</option>
                                        @foreach($eventTypes as $value => $label)
                                            <option value="{{ $value }}" 
                                                    {{ old('event_type', $eventDetails['event_type'] ?? '') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="venue" class="form-label">Venue Address *</label>
                            <textarea class="form-control @error('venue') is-invalid @enderror" 
                                      id="venue" 
                                      name="venue" 
                                      rows="3" 
                                      placeholder="Enter complete venue address including city"
                                      required>{{ old('venue', $eventDetails['venue'] ?? '') }}</textarea>
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="number_of_pax" class="form-label">Number of Attendees *</label>
                            <input type="number" 
                                   class="form-control @error('number_of_pax') is-invalid @enderror" 
                                   id="number_of_pax" 
                                   name="number_of_pax" 
                                   value="{{ old('number_of_pax', $eventDetails['number_of_pax'] ?? '') }}"
                                   min="1"
                                   max="10000"
                                   placeholder="Expected number of guests"
                                   required>
                            @error('number_of_pax')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <h4 class="mt-4 mb-3">Event Timeline</h4>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="installation_time" class="form-label">Setup Time *</label>
                                    <input type="time" 
                                           class="form-control @error('installation_time') is-invalid @enderror" 
                                           id="installation_time" 
                                           name="installation_time" 
                                           value="{{ old('installation_time', $eventDetails['installation_time'] ?? '') }}"
                                           required>
                                    @error('installation_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text">When our team will arrive to set up</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="event_start_time" class="form-label">Event Start Time *</label>
                                    <input type="time" 
                                           class="form-control @error('event_start_time') is-invalid @enderror" 
                                           id="event_start_time" 
                                           name="event_start_time" 
                                           value="{{ old('event_start_time', $eventDetails['event_start_time'] ?? '') }}"
                                           required>
                                    @error('event_start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text">Actual event start time</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="dismantle_time" class="form-label">Pack-up Time *</label>
                                    <input type="time" 
                                           class="form-control @error('dismantle_time') is-invalid @enderror" 
                                           id="dismantle_time" 
                                           name="dismantle_time" 
                                           value="{{ old('dismantle_time', $eventDetails['dismantle_time'] ?? '') }}"
                                           required>
                                    @error('dismantle_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text">When we'll pack up equipment</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="special_requests" class="form-label">Special Requests (Optional)</label>
                            <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                      id="special_requests" 
                                      name="special_requests" 
                                      rows="3" 
                                      placeholder="Any special requirements or instructions for your event">{{ old('special_requests', $eventDetails['special_requests'] ?? '') }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-actions">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Cart
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                Continue to Customer Info<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h3 class="summary-title">Order Summary</h3>
                    
                    <div class="summary-items">
                        @foreach($cart['items'] as $item)
                            <div class="summary-item">
                                <div class="item-info">
                                    <img src="{{ $item['image'] ?? 'https://via.placeholder.com/60' }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="item-image">
                                    <div>
                                        <div class="item-name">{{ $item['name'] }}</div>
                                        <div class="item-details">
                                            @if($item['type'] == 'product')
                                                Qty: {{ $item['quantity'] }} × {{ $item['rental_days'] ?? 1 }} day(s)
                                            @elseif($item['type'] == 'service_provider')
                                                {{ $item['duration_text'] ?? '' }}
                                            @else
                                                Qty: {{ $item['quantity'] ?? 1 }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="item-price">
                                    LKR {{ number_format($item['price'] * ($item['quantity'] ?? 1) * ($item['rental_days'] ?? 1), 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>LKR {{ number_format($cart['subtotal'], 2) }}</span>
                        </div>
                        @if($cart['discount'] > 0)
                            <div class="total-row discount">
                                <span>Discount</span>
                                <span>-LKR {{ number_format($cart['discount'], 2) }}</span>
                            </div>
                        @endif
                        <div class="total-row final">
                            <span>Total</span>
                            <span>LKR {{ number_format($cart['total'], 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="security-info">
                        <i class="fas fa-lock me-2"></i>
                        Your information is secure and encrypted
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .checkout-wrapper {
        min-height: calc(100vh - 200px);
        padding: 40px 0;
        background: var(--bg-dark);
    }

    /* Progress Indicator */
    .checkout-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 40px;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--bg-card);
        border: 2px solid var(--border-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .progress-step.active .step-number {
        background: var(--primary-purple);
        border-color: var(--primary-purple);
        color: white;
    }

    .step-title {
        font-size: 14px;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .progress-step.active .step-title {
        color: var(--primary-purple);
        font-weight: 500;
    }

    .progress-line {
        width: 100px;
        height: 2px;
        background: var(--border-dark);
        margin: 0 20px;
        margin-bottom: 28px;
    }

    /* Main Form Section */
    .checkout-section {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 30px;
    }

    .section-title {
        color: var(--primary-purple);
        font-size: 1.5rem;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-dark);
    }

    .form-label {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
        padding: 12px 15px;
    }

    .form-control:focus {
        background: var(--bg-dark);
        border-color: var(--primary-purple);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
    }

    .form-text {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-dark);
    }

    /* Order Summary */
    .order-summary {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 25px;
        position: sticky;
        top: 20px;
    }

    .summary-title {
        color: var(--text-primary);
        font-size: 1.25rem;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-dark);
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-dark);
    }

    .item-info {
        display: flex;
        gap: 12px;
        flex: 1;
    }

    .item-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
    }

    .item-name {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .item-details {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .item-price {
        font-weight: 600;
        color: var(--primary-purple);
        white-space: nowrap;
    }

    .summary-totals {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--border-dark);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        color: var(--text-primary);
    }

    .total-row.discount {
        color: var(--success);
    }

    .total-row.final {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-purple);
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--border-dark);
    }

    .security-info {
        background: rgba(147, 51, 234, 0.1);
        border: 1px solid var(--primary-purple);
        color: var(--primary-purple);
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 0.875rem;
        text-align: center;
        margin-top: 20px;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .checkout-progress {
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .progress-line {
            display: none;
        }
        
        .order-summary {
            margin-top: 30px;
            position: static;
        }
    }

    @media (max-width: 576px) {
        .checkout-section {
            padding: 20px;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 10px;
        }
        
        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Validate timeline logic
    document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
        const installTime = document.getElementById('installation_time').value;
        const startTime = document.getElementById('event_start_time').value;
        const dismantleTime = document.getElementById('dismantle_time').value;
        
        if (installTime && startTime && installTime >= startTime) {
            e.preventDefault();
            alert('Setup time must be before event start time');
            return false;
        }
        
        if (startTime && dismantleTime && startTime >= dismantleTime) {
            e.preventDefault();
            alert('Event end time must be after event start time');
            return false;
        }
    });
</script>
@endpush
@endsection