@extends('layouts.app')

@section('title', 'Account Settings - Admin Profile')

@section('content')
<div class="container-fluid px-3 px-lg-4 px-xl-5 py-2">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('laptops.index') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Account Settings</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('laptops.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Inventory
            </a>
            @if(Auth::user()->isMainAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-people-fill me-1"></i> Manage Admins
                </a>
            @endif
        </div>
    </div>

    <!-- Header Banner Card -->
    <div class="card mb-4 shadow-sm border-0 bg-white">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle p-3 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-person-gear fs-3"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h2 class="h4 fw-bold text-dark mb-0">Admin Account Settings</h2>
                            @if($user->isMainAdmin())
                                <span class="badge bg-warning text-dark border border-warning px-2 py-1">
                                    <i class="bi bi-shield-fill-check me-1"></i>Main Admin
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                    <i class="bi bi-shield-check me-1"></i>Admin
                                </span>
                            @endif
                        </div>
                        <p class="text-secondary small mb-0">
                            Update your personal administrator credentials, contact email, and account security password.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Card 1: Profile Information (Name & Email) -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge-fill text-primary fs-5"></i>
                    <h5 class="fw-bold text-dark mb-0">Profile Information</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold text-dark">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold text-dark">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required 
                                   autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text small">This email is used to log in to the admin portal.</div>
                        </div>

                        <!-- Role Info (Read-Only) -->
                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary small fw-semibold ">Current Privilege Level:</span>
                                @if($user->isMainAdmin())
                                    <span class="badge bg-warning text-dark fw-bold diagnostic-badge align-self-start align-self-sm-center">
                                        <i class="bi bi-shield-lock-fill me-1"></i>Main Admin (Full Access & Admin Management)
                                    </span>
                                @else
                                    <span class="badge bg-secondary diagnostic-badge">
                                        <i class="bi bi-shield-check me-1"></i>Standard Admin (Catalog & Inventory CRUD)
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="bi bi-check2-circle me-1"></i> Save Profile Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card 2: Change Password -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-key-fill text-warning fs-5"></i>
                    <h5 class="fw-bold text-dark mb-0">Change Password</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold text-dark">
                                Current Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password" 
                                   required 
                                   autocomplete="current-password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-dark">
                                New Password <span class="text-danger">*</span>
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

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold text-dark">
                                Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Repeat new password"
                                   required 
                                   autocomplete="new-password">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning text-dark fw-bold px-4">
                                <i class="bi bi-shield-lock me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* ---- Diagnostic badge: wrap safely on mobile without breaking viewport ---- */
    .diagnostic-badge {
        white-space: normal !important;
        overflow-wrap: anywhere;
        word-break: break-word;
        max-width: 100%;
        line-height: 1.35;
    }
    @media (min-width: 576px) {
        .diagnostic-badge {
            max-width: 65%;
        }
    }
</style>
@endpush

@endsection
