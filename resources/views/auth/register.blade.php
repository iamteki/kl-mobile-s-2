@extends('layouts.app')

@section('title', 'Create Account - KL Mobile DJ & Events')

@section('content')
<div class="auth-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <h2 class="auth-title">Create Your Account</h2>
                        <p class="auth-subtitle">Join us for exclusive deals and instant bookings</p>
                    </div>

                    @if ($errors->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="auth-form">
                        @csrf

                        <!-- Account Type Selection -->
                        <div class="account-type-selector mb-4">
                            <label class="form-label">Account Type</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="radio" 
                                           class="btn-check" 
                                           name="customer_type" 
                                           id="individual" 
                                           value="individual" 
                                           {{ old('customer_type', 'individual') == 'individual' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100" for="individual">
                                        <i class="fas fa-user mb-2"></i>
                                        <span class="d-block">Individual</span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" 
                                           class="btn-check" 
                                           name="customer_type" 
                                           id="corporate" 
                                           value="corporate"
                                           {{ old('customer_type') == 'corporate' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100" for="corporate">
                                        <i class="fas fa-building mb-2"></i>
                                        <span class="d-block">Corporate</span>
                                    </label>
                                </div>
                            </div>
                            @error('customer_type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="John Doe"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="tel" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="+60 12 345 6789"
                                               required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="john@example.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Corporate Fields -->
                        <div id="corporate-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="company" class="form-label">Company Name *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-building"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('company') is-invalid @enderror" 
                                                   id="company" 
                                                   name="company" 
                                                   value="{{ old('company') }}" 
                                                   placeholder="ABC Sdn Bhd">
                                            @error('company')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="company_registration" class="form-label">Registration No. *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-id-badge"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('company_registration') is-invalid @enderror" 
                                                   id="company_registration" 
                                                   name="company_registration" 
                                                   value="{{ old('company_registration') }}" 
                                                   placeholder="123456-K">
                                            @error('company_registration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Address (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="2" 
                                          placeholder="Enter your address">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Min. 8 characters"
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirm password"
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input @error('newsletter') is-invalid @enderror" 
                                   type="checkbox" 
                                   name="newsletter" 
                                   id="newsletter" 
                                   value="1"
                                   {{ old('newsletter') ? 'checked' : '' }}>
                            <label class="form-check-label" for="newsletter">
                                Subscribe to our newsletter for exclusive deals and updates
                            </label>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" 
                                   name="terms" 
                                   id="terms" 
                                   value="1"
                                   {{ old('terms') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="{{ route('terms') }}" target="_blank">Terms & Conditions</a> 
                                and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a> *
                            </label>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i> Create Account
                        </button>

                        <div class="text-center">
                            <p class="mb-0">Already have an account? 
                                <a href="{{ route('login') }}" class="login-link">
                                    Sign in here
                                </a>
                            </p>
                        </div>
                    </form>

                    @if(session()->has('cart') && count(session('cart')) > 0)
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Create an account to continue with your {{ count(session('cart')) }} item(s) checkout.
                        </div>
                    @endif
                </div>

                <div class="auth-footer text-center mt-4">
                    <p class="mb-0">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>Back to Home
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .auth-wrapper {
        min-height: calc(100vh - 200px);
        padding: 60px 0;
        background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(0, 0, 0, 0.95) 100%);
    }

    .auth-card {
        background: var(--bg-card);
        border: 1px solid var(--border-dark);
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .auth-title {
        color: var(--primary-purple);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .auth-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
    }

    .account-type-selector .btn-outline-primary {
        background: var(--bg-dark);
        border: 2px solid var(--border-dark);
        color: var(--text-secondary);
        padding: 20px;
        transition: all 0.3s ease;
    }

    .account-type-selector .btn-check:checked + .btn-outline-primary {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
        border-color: var(--primary-purple);
        color: white;
    }

    .account-type-selector .btn-outline-primary i {
        font-size: 1.5rem;
        display: block;
    }

    .form-label {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 8px;
    }

    .input-group-text {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        color: var(--primary-purple);
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

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    textarea.form-control {
        resize: none;
    }

    .toggle-password {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        color: var(--text-secondary);
    }

    .toggle-password:hover {
        background: var(--bg-dark);
        border-color: var(--primary-purple);
        color: var(--primary-purple);
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

    .form-check-label a {
        color: var(--primary-purple);
        text-decoration: none;
    }

    .form-check-label a:hover {
        text-decoration: underline;
    }

    .login-link {
        color: var(--primary-purple);
        text-decoration: none;
        font-weight: 500;
    }

    .login-link:hover {
        color: var(--secondary-purple);
        text-decoration: underline;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-purple) 0%, var(--secondary-purple) 100%);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(147, 51, 234, 0.4);
    }

    .alert {
        background: var(--bg-dark);
        border: 1px solid;
        color: var(--text-primary);
    }

    .alert-danger {
        border-color: var(--danger);
        background: rgba(239, 68, 68, 0.1);
    }

    .alert-info {
        border-color: var(--primary-purple);
        background: rgba(147, 51, 234, 0.1);
    }

    .auth-footer {
        color: var(--text-secondary);
    }

    .auth-footer a {
        color: var(--text-secondary);
    }

    .auth-footer a:hover {
        color: var(--primary-purple);
    }

    @media (max-width: 768px) {
        .auth-card {
            padding: 30px 20px;
        }

        .auth-title {
            font-size: 1.5rem;
        }

        .account-type-selector .btn-outline-primary {
            padding: 15px;
        }

        .account-type-selector .btn-outline-primary i {
            font-size: 1.2rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input[type="password"], input[type="text"]');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Show/hide corporate fields
    const customerTypeRadios = document.querySelectorAll('input[name="customer_type"]');
    const corporateFields = document.getElementById('corporate-fields');

    function toggleCorporateFields() {
        const selectedType = document.querySelector('input[name="customer_type"]:checked').value;
        
        if (selectedType === 'corporate') {
            corporateFields.style.display = 'block';
            // Make corporate fields required
            document.getElementById('company').setAttribute('required', 'required');
            document.getElementById('company_registration').setAttribute('required', 'required');
        } else {
            corporateFields.style.display = 'none';
            // Remove required attribute
            document.getElementById('company').removeAttribute('required');
            document.getElementById('company_registration').removeAttribute('required');
        }
    }

    customerTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleCorporateFields);
    });

    // Check on page load
    toggleCorporateFields();
</script>
@endpush
@endsection