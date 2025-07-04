@extends('layouts.app')

@section('title', 'Payment - Checkout')

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
            <div class="progress-step completed">
                <span class="step-number"><i class="fas fa-check"></i></span>
                <span class="step-title">Customer Info</span>
            </div>
            <div class="progress-line completed"></div>
            <div class="progress-step active">
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
                        <i class="fas fa-credit-card me-2"></i>Payment Information
                    </h2>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('checkout.payment') }}" id="payment-form" class="checkout-form">
                        @csrf
                        <input type="hidden" name="payment_intent_id" id="payment_intent_id" value="">
                        <input type="hidden" name="payment_method_id" id="payment_method_id" value="">
                        
                        <!-- Payment Methods -->
                        <div class="payment-methods mb-4">
                            <h4 class="mb-3">Select Payment Method</h4>
                            <div class="payment-method-options">
                                <div class="payment-option active" data-method="card">
                                    <div class="option-header">
                                        <input type="radio" name="payment_type" value="card" checked>
                                        <label>
                                            <i class="fas fa-credit-card"></i>
                                            Credit/Debit Card
                                        </label>
                                        <img src="/images/payment-cards.png" alt="Accepted Cards" class="accepted-cards">
                                    </div>
                                </div>
                                
                                <div class="payment-option" data-method="bank">
                                    <div class="option-header">
                                        <input type="radio" name="payment_type" value="bank" disabled>
                                        <label>
                                            <i class="fas fa-university"></i>
                                            Bank Transfer
                                            <span class="badge bg-secondary ms-2">Coming Soon</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Payment Form -->
                        <div id="card-payment" class="payment-form-section">
                            <h4 class="mb-3">Card Details</h4>
                            
                            <div class="form-group mb-4">
                                <label for="cardholder-name" class="form-label">Cardholder Name *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="cardholder-name" 
                                       placeholder="John Doe"
                                       required>
                            </div>
                            
                            <!-- Stripe Card Element -->
                            <div class="form-group mb-4">
                                <label class="form-label">Card Information *</label>
                                <div id="card-element" class="form-control">
                                    <!-- Stripe Card Element will be inserted here -->
                                </div>
                                <div id="card-errors" class="invalid-feedback d-block" role="alert"></div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="form-label">Billing Address</label>
                                <div class="billing-info">
                                    <p class="mb-1"><strong>{{ $customerInfo['name'] }}</strong></p>
                                    <p class="mb-1">{{ $customerInfo['address'] }}</p>
                                    <p class="mb-0">{{ $customerInfo['phone'] }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" 
                                   id="terms" 
                                   name="terms" 
                                   value="1"
                                   required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a> 
                                and understand that this booking is subject to availability confirmation. *
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="security-badges mb-4">
                            <div class="badge-item">
                                <i class="fas fa-lock"></i>
                                <span>256-bit SSL Encryption</span>
                            </div>
                            <div class="badge-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>PCI DSS Compliant</span>
                            </div>
                            <div class="badge-item">
                                <i class="fab fa-stripe"></i>
                                <span>Powered by Stripe</span>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="{{ route('checkout.customer-info') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Customer Info
                            </a>
                            <button type="submit" id="submit-button" class="btn btn-primary btn-lg">
                                <span id="button-text">
                                    <i class="fas fa-lock me-2"></i>Complete Booking - LKR {{ number_format($total, 2) }}
                                </span>
                                <span id="spinner" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Additional Information -->
                <div class="info-section mt-4">
                    <h5><i class="fas fa-info-circle me-2"></i>Important Information</h5>
                    <ul>
                        <li>Your card will be charged immediately upon booking confirmation</li>
                        <li>You will receive a confirmation email with booking details</li>
                        <li>Our team will contact you 24 hours before the event for final coordination</li>
                        <li>Cancellation must be made at least 48 hours before the event for a full refund</li>
                    </ul>
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
                            <span class="detail-value">{{ \Carbon\Carbon::parse($eventDetails['event_date'])->format('d M Y') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Time:</span>
                            <span class="detail-value">{{ $eventDetails['event_start_time'] }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Venue:</span>
                            <span class="detail-value">{{ Str::limit($eventDetails['venue'], 30) }}</span>
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
                            <span>LKR {{ number_format($subtotal, 2) }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="total-row discount">
                                <span>Discount ({{ $cart['coupon'] }})</span>
                                <span>-LKR {{ number_format($discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="total-row">
                            <span>Delivery Charge</span>
                            <span>LKR {{ number_format($deliveryCharge, 2) }}</span>
                        </div>
                        @if($tax > 0)
                            <div class="total-row">
                                <span>Tax</span>
                                <span>LKR {{ number_format($tax, 2) }}</span>
                            </div>
                        @endif
                        <div class="total-row final">
                            <span>Total Due</span>
                            <span>LKR {{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="guarantee-section mt-4">
                        <h6><i class="fas fa-check-circle text-success me-2"></i>Our Guarantee</h6>
                        <ul class="small mb-0">
                            <li>100% Secure Payment</li>
                            <li>Best Price Guarantee</li>
                            <li>Professional Service</li>
                            <li>On-time Delivery</li>
                        </ul>
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

    /* Progress styles same as before */
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

    /* Payment Section */
    .checkout-section {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 30px;
    }

    .payment-method-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .payment-option {
        border: 2px solid var(--border-dark);
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-option.active {
        border-color: var(--primary-purple);
        background: rgba(147, 51, 234, 0.1);
    }

    .payment-option:hover:not(.active) {
        border-color: var(--text-secondary);
    }

    .option-header {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .option-header input[type="radio"] {
        width: 20px;
        height: 20px;
    }

    .option-header label {
        flex: 1;
        margin: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .accepted-cards {
        height: 24px;
        margin-left: auto;
    }

    /* Stripe Card Element */
    #card-element {
        padding: 15px;
        background: var(--bg-dark);
        min-height: 50px;
    }

    #card-element.StripeElement--focus {
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
    }

    #card-element.StripeElement--invalid {
        border-color: var(--danger);
    }

    #card-errors {
        margin-top: 0.5rem;
    }

    .billing-info {
        background: var(--bg-dark);
        padding: 15px;
        border-radius: 8px;
        border: 1px solid var(--border-dark);
    }

    .billing-info p {
        color: var(--text-primary);
        line-height: 1.6;
    }

    /* Security Badges */
    .security-badges {
        display: flex;
        justify-content: center;
        gap: 30px;
        padding: 20px;
        background: rgba(147, 51, 234, 0.05);
        border-radius: 10px;
        border: 1px solid rgba(147, 51, 234, 0.2);
    }

    .badge-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .badge-item i {
        color: var(--primary-purple);
    }

    /* Info Section */
    .info-section {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        padding: 20px;
    }

    .info-section h5 {
        color: var(--primary-purple);
        margin-bottom: 15px;
    }

    .info-section ul {
        margin: 0;
        padding-left: 20px;
    }

    .info-section li {
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    /* Guarantee Section */
    .guarantee-section {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid var(--success);
        border-radius: 10px;
        padding: 15px;
    }

    .guarantee-section h6 {
        color: var(--text-primary);
        margin-bottom: 10px;
    }

    .guarantee-section ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .guarantee-section li {
        color: var(--text-secondary);
        padding-left: 20px;
        position: relative;
        margin-bottom: 5px;
    }

    .guarantee-section li:before {
        content: "✓";
        position: absolute;
        left: 0;
        color: var(--success);
    }

    /* Submit Button States */
    #submit-button {
        min-width: 300px;
        position: relative;
    }

    #submit-button:disabled {
        cursor: not-allowed;
        opacity: 0.7;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .security-badges {
            flex-wrap: wrap;
            gap: 15px;
        }
        
        #submit-button {
            min-width: auto;
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .payment-option {
            padding: 15px;
        }
        
        .option-header {
            font-size: 0.9rem;
        }
        
        .security-badges {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<script>
    // Initialize Stripe
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    const clientSecret = '{{ $clientSecret }}';
    
    // Custom styling for Stripe Elements
    const style = {
        base: {
            color: '#e2e8f0',
            fontFamily: '"Inter", sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#64748b'
            }
        },
        invalid: {
            color: '#ef4444',
            iconColor: '#ef4444'
        }
    };
    
    // Create card element
    const cardElement = elements.create('card', { style: style });
    cardElement.mount('#card-element');
    
    // Handle real-time validation errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Disable submit button and show spinner
        submitButton.disabled = true;
        buttonText.classList.add('d-none');
        spinner.classList.remove('d-none');
        
        // Get cardholder name
        const cardholderName = document.getElementById('cardholder-name').value;
        
        // Confirm payment with Stripe
        const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardholderName,
                    email: '{{ $customerInfo['email'] }}',
                    phone: '{{ $customerInfo['phone'] }}',
                    address: {
                        line1: '{{ $customerInfo['address'] }}',
                        city: 'Colombo',
                        country: 'LK'
                    }
                }
            }
        });
        
        if (error) {
            // Show error to customer
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            
            // Re-enable submit button
            submitButton.disabled = false;
            buttonText.classList.remove('d-none');
            spinner.classList.add('d-none');
        } else {
            // Payment succeeded
            // Set hidden inputs
            document.getElementById('payment_intent_id').value = paymentIntent.id;
            document.getElementById('payment_method_id').value = paymentIntent.payment_method;
            
            // Submit form
            form.submit();
        }
    });
    
    // Handle payment method selection
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('active');
            });
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            if (radio && !radio.disabled) {
                radio.checked = true;
            }
        });
    });
</script>
@endpush
@endsection