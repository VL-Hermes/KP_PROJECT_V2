@extends('layouts.app')

@section('title', 'Add New Laptop - Inventory Management')

@section('content')
<div class="container-fluid px-3 px-lg-4 px-xl-5 py-2">
    <!-- Form Wrapper -->
    <form action="{{ route('laptops.store') }}" method="POST" id="laptop-form" enctype="multipart/form-data">
        @csrf

        <!-- Top Breadcrumb & Action Toolbar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('laptops.index') }}" class="text-decoration-none">Inventory</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Laptop</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('laptops.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Inventory
                </a>
                <button type="submit" class="btn btn-success btn-sm fw-bold px-3 d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-check2-circle"></i> Save Laptop
                </button>
            </div>
        </div>

        <!-- Header Banner Card -->
        <div class="card mb-4 shadow-sm border-0 bg-white">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning-subtle p-3 text-warning d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                        <i class="bi bi-laptop fs-3"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold text-dark mb-1">Add Used Laptop to Inventory</h2>
                        <p class="text-secondary small mb-0">
                            Enter hardware specifications. Core hardware is required, while diagnostic health and peripheral details are optional.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optimized Multi-Column Form -->
        @include('inventory._form')
    </form>
</div>
@endsection
