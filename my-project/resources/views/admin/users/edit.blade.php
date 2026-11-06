@extends('layouts.app')

@section('title', 'Edit Administrator — ' . $user->name)

@section('content')
<div class="container-fluid px-3 px-lg-4 px-xl-5 py-2">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('laptops.index') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none">Manage Admins</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit — {{ $user->name }}</li>
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
                <div class="rounded-circle bg-warning-subtle p-3 text-warning d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                    <i class="bi bi-person-gear fs-3"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h2 class="h4 fw-bold text-dark mb-0">Edit Administrator Account</h2>
                        @if($user->is_main_admin)
                            <span class="badge bg-warning text-dark"><i class="bi bi-shield-fill-check me-1"></i>Main Admin</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-person-badge me-1"></i>Admin</span>
                        @endif
                    </div>
                    <p class="text-secondary small mb-0 mt-1">
                        Update profile details and access level for <strong>{{ $user->email }}</strong>.
                        Leave the password fields blank to keep the current password.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editAdminForm">
                @csrf
                @method('PUT')

                <!-- ── Account Information ── -->
                <div class="card shadow-sm border-0 bg-white mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge-fill text-primary fs-5"></i>
                        <h5 class="fw-bold text-dark mb-0">Account Information</h5>
                    </div>
                    <div class="card-body p-4">

                        <!-- Full Name -->
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

                        <!-- Email Address -->
                        <div class="mb-0">
                            <label for="email" class="form-label fw-bold text-dark">
                                Email Address (Login ID) <span class="text-danger">*</span>
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
                            <div class="form-text small">This email is used to log in at <code>/admin</code>.</div>
                        </div>
                    </div>
                </div>

                <!-- ── Change Password (Optional) ── -->
                <div class="card shadow-sm border-0 bg-white mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-key-fill text-secondary fs-5"></i>
                        <h5 class="fw-bold text-dark mb-0">Change Password <span class="text-secondary fw-normal small">(optional)</span></h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info d-flex align-items-center gap-2 py-2 small" role="alert">
                            <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                            Leave both fields blank to keep the current password unchanged.
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold text-dark">New Password</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Minimum 8 characters"
                                   autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-0">
                            <label for="password_confirmation" class="form-label fw-bold text-dark">Confirm New Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Repeat new password"
                                   autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <!-- ── Role / Privilege Level ── -->
                <div class="card shadow-sm border-0 bg-white mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-shield-fill-check text-warning fs-5"></i>
                        <h5 class="fw-bold text-dark mb-0">Access Level</h5>
                    </div>
                    <div class="card-body p-4">
                        @if($user->id === auth()->id())
                            {{-- Cannot change own role --}}
                            <div class="alert alert-warning d-flex align-items-center gap-2 py-2 small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                                You cannot change your own access level to prevent accidental privilege loss.
                            </div>
                            <input type="hidden" name="is_main_admin" value="{{ $user->is_main_admin ? '1' : '0' }}">
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-2">
                                @if($user->is_main_admin)
                                    <i class="bi bi-shield-fill-check text-warning fs-5"></i>
                                    <div>
                                        <div class="fw-bold text-dark">Main Admin <span class="badge bg-warning text-dark ms-1">Active</span></div>
                                        <small class="text-secondary">Full access including admin user management.</small>
                                    </div>
                                @else
                                    <i class="bi bi-person-badge fs-5 text-secondary"></i>
                                    <div>
                                        <div class="fw-bold text-dark">Admin <span class="badge bg-secondary ms-1">Active</span></div>
                                        <small class="text-secondary">Can manage laptop inventory and own profile.</small>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="form-check form-switch mb-1">
                                    <input class="form-check-input fs-5 ms-0 me-2"
                                           type="checkbox"
                                           role="switch"
                                           id="is_main_admin"
                                           name="is_main_admin"
                                           value="1"
                                           {{ old('is_main_admin', $user->is_main_admin) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="is_main_admin">
                                        <i class="bi bi-shield-fill-check text-warning me-1"></i> Grant "Main Admin" Privileges
                                    </label>
                                </div>
                                <small class="text-secondary d-block ps-4">
                                    Main Admins have full access to create, edit, and delete other administrator accounts.
                                    Regular Admins can only manage laptop inventory and their own profile.
                                </small>
                            </div>
                            @error('is_main_admin')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                </div>

                <!-- ── Danger Zone (Delete) ── -->
                @if($user->id !== auth()->id())
                <div class="card shadow-sm border border-danger-subtle bg-white mb-4">
                    <div class="card-header bg-danger-subtle py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-trash3-fill text-danger fs-5"></i>
                        <h5 class="fw-bold text-danger mb-0">Danger Zone</h5>
                    </div>
                    <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <div class="fw-bold text-dark">Delete This Administrator</div>
                            <small class="text-secondary">This action is permanent and cannot be undone.</small>
                        </div>
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                            <i class="bi bi-trash3 me-1"></i> Delete Admin
                        </button>
                    </div>
                </div>
                @endif

                <!-- ── Form Actions ── -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-check-circle-fill me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@if($user->id !== auth()->id())
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-2">You are about to permanently delete the administrator account:</p>
                <div class="bg-light rounded-3 p-3 border mb-3">
                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                    <div class="text-secondary small">{{ $user->email }}</div>
                    <div class="mt-1">
                        @if($user->is_main_admin)
                            <span class="badge bg-warning text-dark"><i class="bi bi-shield-fill-check me-1"></i>Main Admin</span>
                        @else
                            <span class="badge bg-secondary">Admin</span>
                        @endif
                    </div>
                </div>
                <p class="text-danger small mb-0"><i class="bi bi-exclamation-circle me-1"></i>This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="bi bi-trash3-fill me-1"></i> Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

