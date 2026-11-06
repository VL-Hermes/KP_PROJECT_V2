@extends('layouts.app')

@section('title', 'Manage Administrators - Main Admin Portal')

@section('content')
<div class="container-fluid px-3 px-lg-4 px-xl-5 py-2">
    <!-- Top Action Bar & Breadcrumbs -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('laptops.index') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manage Admins</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('laptops.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Inventory
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-person-plus-fill"></i> Add New Admin
            </a>
        </div>
    </div>

    <!-- Header Banner Card -->
    <div class="card mb-4 shadow-sm border-0 bg-white">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning-subtle p-3 text-warning d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-people-fill fs-3 text-dark"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h2 class="h4 fw-bold text-dark mb-0">Administrator Management</h2>
                            <span class="badge bg-warning text-dark border border-warning px-2 py-1">
                                <i class="bi bi-shield-lock-fill me-1"></i>Main Admin Area
                            </span>
                        </div>
                        <p class="text-secondary small mb-0">
                            Create and manage administrator accounts, set privileges, and maintain administrative credentials.
                        </p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <div class="card bg-light border p-2 px-3 text-center">
                        <small class="text-muted fw-semibold">Total Admins</small>
                        <span class="fs-5 fw-bold text-dark">{{ $totalAdmins }}</span>
                    </div>
                    <div class="card bg-light border p-2 px-3 text-center">
                        <small class="text-muted fw-semibold">Main Admins</small>
                        <span class="fs-5 fw-bold text-warning">{{ $totalMainAdmins }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admins Table Card -->
    <div class="card shadow-sm border-0 bg-white">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-lines-fill text-primary me-2"></i>Active Administrators List
            </h5>
            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                {{ $admins->total() }} Registered
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3 py-3" style="width: 70px;">ID</th>
                        <th scope="col" class="py-3">Administrator Name</th>
                        <th scope="col" class="py-3">Email Address</th>
                        <th scope="col" class="py-3 text-center">Privilege Role</th>
                        <th scope="col" class="py-3 text-center">Created At</th>
                        <th scope="col" class="py-3 text-end pe-3" style="min-width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        <tr class="{{ $admin->id === auth()->id() ? 'table-primary-subtle' : '' }}">
                            <td class="ps-3 fw-bold text-muted small">
                                #{{ $admin->id }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light border p-2 text-center d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                        @if($admin->isMainAdmin())
                                            <i class="bi bi-shield-fill-check text-warning fs-5"></i>
                                        @else
                                            <i class="bi bi-person-fill text-secondary fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">
                                            {{ $admin->name }}
                                            @if($admin->id === auth()->id())
                                                <span class="badge bg-primary ms-1" style="font-size: 0.68rem;">You</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-secondary small">
                                    <i class="bi bi-envelope me-1 text-muted"></i>{{ $admin->email }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($admin->isMainAdmin())
                                    <span class="badge bg-warning text-dark border border-warning px-3 py-2 rounded-pill fw-bold">
                                        <i class="bi bi-shield-fill-check me-1"></i>Main Admin
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                                        <i class="bi bi-shield-check me-1"></i>Admin
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                <span class="small text-secondary">
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>{{ $admin->created_at ? $admin->created_at->format('d M Y') : '-' }}
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.users.edit', $admin) }}" class="btn btn-outline-primary" title="Edit Admin Credentials & Role">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </a>
                                    @if($admin->id !== auth()->id())
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="Delete Administrator"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteAdminModal" 
                                                data-action="{{ route('admin.users.destroy', $admin) }}"
                                                data-name="{{ $admin->name }} ({{ $admin->email }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary" disabled title="Cannot delete your currently logged-in account">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-3">
                                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="fw-bold mt-2">No Administrators Found</h5>
                                    <p class="text-muted small">No other administrators have been registered.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($admins->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Admin Confirmation Modal -->
<div class="modal fade" id="deleteAdminModal" tabindex="-1" aria-labelledby="deleteAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteAdminModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Admin Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-person-x-fill text-danger mb-3" style="font-size: 3.5rem;"></i>
                <h5 class="fw-bold text-dark">Are you sure?</h5>
                <p class="text-secondary small mb-2">
                    You are about to permanently delete administrator:
                </p>
                <div class="p-2 bg-light rounded border fw-bold text-dark mb-3" id="deleteAdminTargetName">
                    Admin
                </div>
                <div class="alert alert-warning py-2 px-3 small text-start mb-0">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    This administrator will immediately lose all access to the admin dashboard.
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteAdminForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-bold">
                        <i class="bi bi-trash-fill me-1"></i> Delete Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteAdminModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const action = button.getAttribute('data-action');
                const name = button.getAttribute('data-name');

                const form = document.getElementById('deleteAdminForm');
                const targetText = document.getElementById('deleteAdminTargetName');

                if (form) form.action = action;
                if (targetText) targetText.textContent = name;
            });
        }
    });
</script>
@endpush
@endsection

