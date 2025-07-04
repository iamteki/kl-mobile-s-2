@extends('layouts.app')

@section('title', 'Profile Settings - KL Mobile DJ & Events')

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
                <div class="profile-section">
                    <h1 class="page-title">Profile Settings</h1>
                    <p class="page-subtitle">Manage your personal information and preferences</p>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>Please correct the errors below.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('account.profile.update') }}" class="profile-form">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <div class="form-section">
                            <h3 class="section-title">Personal Information</h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name *</label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $user->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address *</label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text">We'll use this for booking confirmations</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number *</label>
                                        <input type="tel" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', $customer?->phone) }}" 
                                               placeholder="+94 77 123 4567"
                                               required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text">For delivery coordination</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address *</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" 
                                                  name="address" 
                                                  rows="3" 
                                                  placeholder="Enter your complete address"
                                                  required>{{ old('address', $customer?->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Information (Optional) -->
                        <div class="form-section">
                            <h3 class="section-title">Company Information (Optional)</h3>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="company">Company Name</label>
                                        <input type="text" 
                                               class="form-control @error('company') is-invalid @enderror" 
                                               id="company" 
                                               name="company" 
                                               value="{{ old('company', $customer?->company) }}">
                                        @error('company')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="company_registration">Registration Number</label>
                                        <input type="text" 
                                               class="form-control @error('company_registration') is-invalid @enderror" 
                                               id="company_registration" 
                                               name="company_registration" 
                                               value="{{ old('company_registration', $customer?->company_registration) }}">
                                        @error('company_registration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tax_id">Tax ID</label>
                                        <input type="text" 
                                               class="form-control @error('tax_id') is-invalid @enderror" 
                                               id="tax_id" 
                                               name="tax_id" 
                                               value="{{ old('tax_id', $customer?->tax_id) }}">
                                        @error('tax_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Communication Preferences -->
                        <div class="form-section">
                            <h3 class="section-title">Communication Preferences</h3>
                            
                            <div class="preference-options">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="newsletter_subscribed" 
                                           name="newsletter_subscribed" 
                                           value="1"
                                           {{ old('newsletter_subscribed', $customer?->newsletter_subscribed) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="newsletter_subscribed">
                                        <strong>Email Newsletter</strong>
                                        <span>Receive updates about new equipment, special offers, and event tips</span>
                                    </label>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="sms_notifications" 
                                           name="sms_notifications" 
                                           value="1"
                                           {{ old('sms_notifications', $customer?->sms_notifications ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_notifications">
                                        <strong>SMS Notifications</strong>
                                        <span>Receive booking confirmations and delivery updates via SMS</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Account Stats -->
                        <div class="form-section">
                            <h3 class="section-title">Account Statistics</h3>
                            
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <span class="stat-value">{{ $customer?->total_bookings ?? 0 }}</span>
                                    <span class="stat-label">Total Bookings</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-value">LKR {{ number_format($customer?->total_spent ?? 0, 2) }}</span>
                                    <span class="stat-label">Total Spent</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-value">{{ $user->created_at->format('M Y') }}</span>
                                    <span class="stat-label">Member Since</span>
                                </div>
                                @if($customer?->last_booking_date)
                                    <div class="stat-item">
                                        <span class="stat-value">{{ $customer->last_booking_date->format('d M Y') }}</span>
                                        <span class="stat-label">Last Booking</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('account.dashboard') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .profile-section {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 30px;
    }

    .page-title {
        color: var(--primary-purple);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .page-subtitle {
        color: var(--text-secondary);
        margin-bottom: 30px;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 40px;
        padding-bottom: 40px;
        border-bottom: 1px solid var(--border-dark);
    }

    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 30px;
    }

    .section-title {
        color: var(--text-primary);
        font-size: 1.25rem;
        margin-bottom: 25px;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
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
        margin-top: 5px;
    }

    /* Preference Options */
    .preference-options {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .preference-options .form-check {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        padding: 20px;
        transition: all 0.3s ease;
    }

    .preference-options .form-check:hover {
        border-color: var(--primary-purple);
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        margin-top: 0;
        background-color: var(--bg-dark);
        border-color: var(--border-dark);
    }

    .form-check-input:checked {
        background-color: var(--primary-purple);
        border-color: var(--primary-purple);
    }

    .form-check-label {
        margin-left: 10px;
        cursor: pointer;
    }

    .form-check-label strong {
        display: block;
        color: var(--text-primary);
        margin-bottom: 5px;
    }

    .form-check-label span {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .stat-item {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }

    .stat-value {
        display: block;
        color: var(--primary-purple);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid var(--border-dark);
    }

    /* Alerts */
    .alert {
        background: var(--bg-dark);
        border: 1px solid;
        color: var(--text-primary);
    }

    .alert-success {
        border-color: var(--success);
        background: rgba(34, 197, 94, 0.1);
    }

    .alert-danger {
        border-color: var(--danger);
        background: rgba(239, 68, 68, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-section {
            padding: 20px;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush
@endsection