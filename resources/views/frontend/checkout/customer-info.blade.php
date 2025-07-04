@extends('layouts.app')

@section('title', 'Customer Information - Checkout')

@section('content')
<div class="checkout-wrapper">
    <div class="container">
        <!-- Checkout Progress -->
        <div class="checkout-progress mb-5">
            <div class="progress-step completed">
                <span class="step-number"><i class="fas fa-check"></i></span>
                <span class="step-title">Event Details</span>
            </div>
            <div class="progress-line completed"></div>
            <div class="progress-step active">
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
                        <i class="fas fa-user me-2"></i>Customer Information
                    </h2>
                    
                    <form method="POST" action="{{ route('checkout.customer-info') }}" class="checkout-form">
                        @csrf
                        
                        <h4 class="mb-3">Contact Information</h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $customerInfo['name']) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $customerInfo['email']) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text">We'll send booking confirmation to this email</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $customerInfo['phone']) }}"
                                           placeholder="+94 77 123 4567"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text">For delivery coordination</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="company" class="form-label">Company Name (Optional)</label>
                                    <input type="text" 
                                           class="form-control @error('company') is-invalid @enderror" 
                                           id="company" 
                                           name="company" 
                                           value="{{ old('company', $customerInfo['company']) }}">
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="mt-4 mb-3">Billing Address</h4>
                        
                        <div class="form-group mb-4">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Enter your complete address"
                                      required>{{ old('address', $customerInfo['address']) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <h4 class="mt-4 mb-3">Delivery Information</h4>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="use_different_delivery" 
                                   name="use_different_delivery" 
                                   value="1"
                                   {{ old('use_different_delivery') ? 'checked' : '' }}>
                            <label class="form-check-label" for="use_different_delivery">
                                Ship to a different address
                            </label>
                        </div>
                        
                        <div id="delivery-address-section" style="display: none;">
                            <div class="form-group mb-4">
                                <label for="delivery_address" class="form-label">Delivery Address *</label>
                                <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                                          id="delivery_address" 
                                          name="delivery_address" 
                                          rows="3" 
                                          placeholder="Enter delivery address">{{ old('delivery_address', $customerInfo['delivery_address'] ?? '') }}</textarea>
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label for="delivery_instructions" class="form-label">Delivery Instructions (Optional)</label>
                            <textarea class="form-control @error('delivery_instructions') is-invalid @enderror" 
                                      id="delivery_instructions" 
                                      name="delivery_instructions" 
                                      rows="2" 
                                      placeholder="Any special delivery instructions or access codes">{{ old('delivery_instructions', $customerInfo['delivery_instructions'] ?? '') }}</textarea>
                            @error('delivery_instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="save_info" 
                                   name="save_info" 
                                   value="1"
                                   {{ old('save_info', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="save_info">
                                Save this information for faster checkout next time
                            </label>
                        </div>
                        
                        <div class="form-actions">
                            <a href="{{ route('checkout.event-details') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Event Details
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                Continue to Payment<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h3 class="summary-title">Order Summary</h3>
                    
                    <!-- Event Details Summary -->
                    <div class="event-summary mb-4">
                        <h5 class="text-primary mb-3">Event Details</h5>
                        <div class="detail-row">
                            <span class="detail-label">Date:</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse(session('checkout.event_details.event_date'))->format('d M Y') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Type:</span>
                            <span class="detail-value">{{ ucfirst(session('checkout.event_details.event_type')) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Venue:</span>
                            <span class="detail-value">{{ Str::limit(session('checkout.event_details.venue'), 30) }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Attendees:</span>
                            <span class="detail-value">{{ session('checkout.event_details.number_of_pax') }} pax</span>
                        </div>
                    </div>
                    
                    <div class="summary-items">
                        @foreach($cart['items'] as $item)
                            <div class="summary-item">
                                <div class="item-info">
                                    <div>
                                        <div class="item-name">{{ $item['name'] }}</div>
                                        <div class="item-details">
                                            @if($item['type'] == 'product')
                                                Qty: {{ $item['quantity'] }}
                                            @elseif($item['type'] == 'service_provider')
                                                {{ $item['duration_text'] ?? '' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="item-price">
                                    LKR {{ number_format($item['price'] * ($item['quantity'] ?? 1), 2) }}
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
                        <div class="total-row">
                            <span>Delivery</span>
                            <span>LKR 500.00</span>
                        </div>
                        <div class="total-row final">
                            <span>Total</span>
                            <span>LKR {{ number_format($cart['total'] + 500, 2) }}</span>
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

    .progress-step.completed .step-number {
        background: var(--success);
        border-color: var(--success);
        color: white;
        font-size: 14px;
    }

    .step-title {
        font-size: 14px;
        color: var(--text-secondary);
        white-space: nowrap;
    }

    .progress-step.active .step-title,
    .progress-step.completed .step-title {
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

    .progress-line.completed {
        background: var(--success);
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

    .form-control {
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

    .form-check-input {
        background-color: var(--bg-dark);
        border-color: var(--border-dark);
    }

    .form-check-input:checked {
        background-color: var(--primary-purple);
        border-color: var(--primary-purple);
    }

    .form-check-label {
        color: var(--text-primary);
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

    .event-summary {
        background: rgba(147, 51, 234, 0.1);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .event-summary h5 {
        font-size: 1rem;
        margin-bottom: 12px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.875rem;
    }

    .detail-label {
        color: var(--text-secondary);
    }

    .detail-value {
        color: var(--text-primary);
        font-weight: 500;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-dark);
    }

    .item-info {
        flex: 1;
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
    // Toggle delivery address section
    const useDifferentDelivery = document.getElementById('use_different_delivery');
    const deliverySection = document.getElementById('delivery-address-section');
    const deliveryAddressField = document.getElementById('delivery_address');
    
    useDifferentDelivery.addEventListener('change', function() {
        if (this.checked) {
            deliverySection.style.display = 'block';
            deliveryAddressField.setAttribute('required', 'required');
        } else {
            deliverySection.style.display = 'none';
            deliveryAddressField.removeAttribute('required');
        }
    });
    
    // Check on page load
    if (useDifferentDelivery.checked) {
        deliverySection.style.display = 'block';
    }
</script>
@endpush
@endsection