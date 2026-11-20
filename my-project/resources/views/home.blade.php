{{-- @extends('layouts.app')

@section('title', 'Dashboard - Laptop Inventory Manager')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Welcome Card -->
            <div class="card shadow-sm border-0 bg-white mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 mb-2 rounded-pill">
                                <i class="bi bi-shield-check me-1"></i> Authenticated Session
                            </span>
                            <h1 class="display-6 fw-bold text-dark mb-2">Welcome to Anugerah Computer</h1>
                            <p class="text-secondary mb-0">
                                You are signed in. You can manage your used laptop stock, enter specifications, or browse the public catalog.
                            </p>
                        </div>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="{{ route('laptops.create') }}" class="btn btn-warning fw-bold px-3 py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-plus-circle-fill"></i> Add Laptop
                            </a>
                            <a href="{{ route('laptops.index') }}" class="btn btn-dark px-3 py-2 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-table"></i> Inventory Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation Cards -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-circle bg-primary-subtle p-3 text-primary">
                                    <i class="bi bi-grid-3x3-gap fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Public Catalog</h5>
                                    <small class="text-muted">Customer-facing view</small>
                                </div>
                            </div>
                            <p class="text-secondary small">
                                Preview how used laptops appear to prospective buyers. Only filled specifications are rendered; missing diagnostics are omitted.
                            </p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary btn-sm">
                                Open Public Catalog <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="rounded-circle bg-warning-subtle p-3 text-warning">
                                    <i class="bi bi-lightning-charge-fill fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Quick-Fill Inventory</h5>
                                    <small class="text-muted">High-speed data entry</small>
                                </div>
                            </div>
                            <p class="text-secondary small">
                                Rapidly log tested machines with one-click standard presets for battery, audio, camera, and SMART health checks.
                            </p>
                            <a href="{{ route('laptops.create') }}" class="btn btn-outline-dark btn-sm">
                                Create with Quick-Fill <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
