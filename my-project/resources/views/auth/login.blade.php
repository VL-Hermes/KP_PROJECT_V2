@extends('layouts.app')

@section('title', 'Admin Portal Login - LaptopHub')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <!-- Admin Login Card -->
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-4 p-md-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-dark text-warning p-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 56px; height: 56px;">
                            <i class="bi bi-shield-lock-fill fs-3"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Administrator Portal</h4>
                        <p class="text-secondary small mb-0">
                            Authorized access only. Sign in to manage used laptop specifications and inventory.
                        </p>
                    </div>

                    <!-- Demo Credentials Hint Alert -->
                    <div class="alert alert-light border small text-secondary d-flex align-items-start gap-2 mb-4 py-2 px-3">
                        <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
                        <div>
                            <div class="fw-bold text-dark">Default Admin Credentials:</div>
                            <div>Email: <code class="text-dark">admin@admin.com</code> &bull; Password: <code class="text-dark">password</code></div>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold text-dark">
                                <i class="bi bi-envelope me-1"></i> Email Address
                            </label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email', 'admin@admin.com') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold text-dark">
                                <i class="bi bi-key me-1"></i> Password
                            </label>
                            <input id="password" 
                                   type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-secondary" for="remember">
                                    Remember my session
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Sign In to Dashboard</span>
                            </button>
                        </div>
                    </form>

                    <!-- Back to Catalog Link -->
                    <div class="text-center mt-4 pt-2 border-top">
                        <a href="{{ route('catalog.index') }}" class="text-secondary small text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> Return to Public Catalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
