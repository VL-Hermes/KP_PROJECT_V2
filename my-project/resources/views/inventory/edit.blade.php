@extends('layouts.app')

@section('title', "Edit Laptop: {$laptop->brand} {$laptop->model}")

@section('content')
<div class="container-fluid px-3 px-lg-4 px-xl-5 py-2">
    <!-- Form Wrapper -->
    <form action="{{ route('laptops.update', $laptop) }}" method="POST" id="laptop-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Top Breadcrumb & Action Toolbar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('laptops.index') }}" class="text-decoration-none">Inventory</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalog.show', $laptop) }}" class="text-decoration-none" target="_blank">{{ $laptop->brand }} {{ $laptop->model }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('catalog.show', $laptop) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                    <i class="bi bi-eye me-1"></i> View Catalog Page
                </a>
                <a href="{{ route('laptops.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Inventory
                </a>
                <button type="submit" class="btn btn-success btn-sm fw-bold px-3 d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-check2-circle"></i> Save Changes
                </button>
            </div>
        </div>

        <!-- Header Card -->
        <div class="card mb-4 shadow-sm border-0 bg-white">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary-subtle p-3 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                            <i class="bi bi-pencil-square fs-3"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Catalog Ref #LAP-{{ str_pad($laptop->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <h2 class="h4 fw-bold text-dark mb-1">Edit Specifications: {{ $laptop->brand }} {{ $laptop->model }}</h2>
                            <p class="text-secondary small mb-0">
                                Update specifications, health diagnostics, or availability. Blank optional fields are omitted from public view.
                            </p>
                        </div>
                    </div>
                    <div>
                        @if($laptop->is_available)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6">
                                <i class="bi bi-check-circle me-1"></i> Currently In Stock
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 fs-6">
                                <i class="bi bi-dash-circle me-1"></i> Reserved / Sold
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Optimized Multi-Column Form -->
        @include('inventory._form')
    </form>
</div>
@endsection
