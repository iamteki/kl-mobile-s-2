@extends('layouts.app')

@section('title', 'Change Password - KL Mobile DJ & Events')

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
                <div class="password-section">
                    <h1 class="page-title">Change Password</h1>
                    <p class="page-subtitle">Keep your account secure by using a strong password</p>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-8">
                            <form method="POST" action="{{ route('account.password.update') }}" class="password-form">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" 
                                               name="current_password" 
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text">Password must be at least 8 characters long</small>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="password-strength mt-3">
                                    <h5>Password Requirements:</h5>
                                    <ul>
                                        <li class="requirement" data-requirement="length">
                                            <i class="fas fa-circle"></i> At least 8 characters long
                                        </li>
                                        <li class="requirement" data-requirement="uppercase">
                                            <i class="fas fa-circle"></i> Contains uppercase letter (recommended)
                                        </li>
                                        <li class="requirement" data-requirement="lowercase">
                                            <i class="fas fa-circle"></i> Contains lowercase letter (recommended)
                                        </li>
                                        <li class="requirement" data-requirement="number">
                                            <i class="fas fa-circle"></i> Contains a number (recommended)
                                        </li>
                                        <li class="requirement" data-requirement="special">
                                            <i class="fas fa-circle"></i> Contains special character (recommended)
                                        </li>
                                    </ul>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-lock me-2"></i>Update Password
                                    </button>
                                    <a href="{{ route('account.dashboard') }}" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-4">
                            <div class="security-tips">
                                <h5><i class="fas fa-shield-alt me-2"></i>Security Tips</h5>
                                <ul>
                                    <li>Use a unique password that you don't use for other accounts</li>
                                    <li>Avoid using personal information in your password</li>
                                    <li>Consider using a password manager</li>
                                    <li>Change your password regularly</li>
                                    <li>Never share your password with anyone</li>
                                </ul>

                                <div class="last-updated">
                                    <p><strong>Last Login:</strong></p>
                                    <p>{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d M Y, h:i A') : 'Never' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .password-section {
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

    /* Form */
    .password-form {
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
    }

    .input-group {
        position: relative;
    }

    .form-control {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        color: var(--text-primary);
        padding: 12px 15px;
        padding-right: 50px;
    }

    .form-control:focus {
        background: var(--bg-dark);
        border-color: var(--primary-purple);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(147, 51, 234, 0.25);
    }

    .toggle-password {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        border-left: none;
        color: var(--text-secondary);
    }

    .toggle-password:hover {
        background: var(--bg-dark);
        border-color: var(--primary-purple);
        color: var(--primary-purple);
    }

    .form-text {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin-top: 5px;
    }

    /* Password Strength */
    .password-strength {
        background: var(--bg-dark);
        border: 1px solid var(--border-dark);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .password-strength h5 {
        color: var(--text-primary);
        font-size: 1rem;
        margin-bottom: 15px;
    }

    .password-strength ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .requirement {
        color: var(--text-secondary);
        padding: 5px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .requirement i {
        font-size: 0.5rem;
    }

    .requirement.met {
        color: var(--success);
    }

    .requirement.met i {
        color: var(--success);
    }

    /* Security Tips */
    .security-tips {
        background: rgba(147, 51, 234, 0.1);
        border: 1px solid var(--primary-purple);
        border-radius: 10px;
        padding: 25px;
    }

    .security-tips h5 {
        color: var(--primary-purple);
        margin-bottom: 20px;
    }

    .security-tips ul {
        padding-left: 20px;
        margin-bottom: 25px;
    }

    .security-tips li {
        color: var(--text-primary);
        margin-bottom: 10px;
        line-height: 1.6;
    }

    .last-updated {
        background: var(--bg-card);
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }

    .last-updated p {
        margin: 0;
        color: var(--text-secondary);
    }

    .last-updated p:first-child {
        margin-bottom: 5px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
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

    .alert ul {
        margin-bottom: 0;
        padding-left: 20px;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .security-tips {
            margin-top: 30px;
        }
    }

    @media (max-width: 768px) {
        .password-section {
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

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.input-group').querySelector('input');
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

    // Password strength checker
    const passwordInput = document.getElementById('password');
    const requirements = document.querySelectorAll('.requirement');

    passwordInput?.addEventListener('input', function() {
        const password = this.value;
        
        // Check each requirement
        requirements.forEach(req => {
            const type = req.dataset.requirement;
            let met = false;
            
            switch(type) {
                case 'length':
                    met = password.length >= 8;
                    break;
                case 'uppercase':
                    met = /[A-Z]/.test(password);
                    break;
                case 'lowercase':
                    met = /[a-z]/.test(password);
                    break;
                case 'number':
                    met = /\d/.test(password);
                    break;
                case 'special':
                    met = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                    break;
            }
            
            if (met) {
                req.classList.add('met');
            } else {
                req.classList.remove('met');
            }
        });
    });
</script>
@endpush
@endsection