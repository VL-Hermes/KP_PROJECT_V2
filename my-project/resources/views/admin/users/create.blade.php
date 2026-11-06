@extends('layouts.app')

@section('title', 'Add New Administrator - Main Admin Portal')

@section('content')
<div class="container-fluid px-3 px-lg-4 px-xl-5 py-2">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('laptops.index') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none">Manage Admins</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Admin</li>
            </ol>
        </nav>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Admins List
            </a>
        </div>
    </div>

    <!-- Header Banner Card -->
    <div class="card mb-4 shadow-sm border-0 bg-white">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary-subtle p-3 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-person-plus-fill fs-3"></i>
                </div>
                <div>
                    <h2 class="h4 fw-bold text-dark mb-1">Create New Administrator Account</h2>
                    <p class="text-secondary small mb-0">
                        Create credentials for a new administrator. You can grant standard inventory manager access or full Main Admin privileges.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge-fill text-primary fs-5"></i>
                    <h5 class="fw-bold text-dark mb-0">Administrator Details</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold text-dark">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   placeholder="e.g. John Doe"
                                   value="{{ old('name') }}" 
                                   required 
                                   autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-dark">
                                Email Address (Login ID) <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   placeholder="admin@example.com"
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small">This email address will be used to log in at <code>/admin</code>.</div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-dark">
                                Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Minimum 8 characters"
                                   required 
                                   autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold text-dark">
                                Confirm Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Repeat password"
                                   required 
                                   autocomplete="new-password">
                        </div>

                        <!-- Role / Privilege Level -->
                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input fs-5 ms-0 me-2" 
                                       type="checkbox" 
                                       role="switch" 
                                       id="is_main_admin" 
                                       name="is_main_admin" 
                                       value="1" 
                                       {{ old('is_main_admin') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="is_main_admin">
                                    <i class="bi bi-shield-fill-check text-warning me-1"></i> Grant "Main Admin" Privileges
                                </label>
                            </div>
                            <small class="text-secondary d-block ps-4">
                                Main Admins have full access to create, edit, and delete other administrator accounts. Regular Admins can only manage laptop inventory and their own profile.
                            </small>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="bi bi-person-check-fill me-1"></i> Create Admin Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

