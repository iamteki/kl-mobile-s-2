@extends('layouts.app')

@section('title', 'Login - KL Mobile DJ & Events')

@section('content')
<div class="auth-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <h2 class="auth-title">Welcome Back</h2>
                        <p class="auth-subtitle">Sign in to continue to your account</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="auth-form">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="Enter your email"
                                       required 
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password"
                                       required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="remember" 
                                       id="remember" 
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In
                        </button>

                        <div class="text-center">
                            <p class="mb-0">Don't have an account? 
                                <a href="{{ route('register') }}" class="register-link">
                                    Create one now
                                </a>
                            </p>
                        </div>
                    </form>

                    @if(session()->has('cart') && count(session('cart')) > 0)
                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            You have {{ count(session('cart')) }} item(s) in your cart. 
                            Sign in to continue with checkout.
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
        padding: 80px 0;
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

    .forgot-link,
    .register-link {
        color: var(--primary-purple);
        text-decoration: none;
        font-weight: 500;
    }

    .forgot-link:hover,
    .register-link:hover {
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

    .alert-success {
        border-color: var(--success);
        background: rgba(34, 197, 94, 0.1);
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
</script>
@endpush
@endsection