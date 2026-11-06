@extends('layouts.app')

@section('title', "{$laptop->brand} {$laptop->model} - Specifications Catalog")

@section('content')
<div class="container py-2">
    <!-- Breadcrumb & Top Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none">Catalog</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalog.index', ['brand' => $laptop->brand]) }}" class="text-decoration-none">{{ $laptop->brand }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $laptop->model }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Catalog
            </a>
            @auth
                <a href="{{ route('laptops.edit', $laptop) }}" class="btn btn-dark btn-sm">
                    <i class="bi bi-pencil-square me-1"></i> Edit Laptop
                </a>
            @endauth
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm d-none d-md-inline-block">
                <i class="bi bi-printer me-1"></i> Print Specs
            </button>
        </div>
    </div>

    <!-- Product Title Card -->
    <div class="card mb-4 shadow-sm border-0 bg-white">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-dark px-3 py-2 fs-6">{{ $laptop->brand }}</span>
                        @if($laptop->is_available)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i> Available in Inventory
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                                <i class="bi bi-dash-circle-fill me-1"></i> Reserved / Currently Unavailable
                            </span>
                        @endif
                    </div>
                    <h1 class="display-6 fw-bold text-dark mb-1">{{ $laptop->brand }} {{ $laptop->model }}</h1>
                    <p class="text-secondary mb-2">Spesifikasi Hardware & Penilaian Diagnostik Lengkap</p>
                    @if($laptop->hasPriceEstimate())
                        <div class="d-inline-flex align-items-baseline gap-2 mt-1 p-2 px-3 rounded-3 bg-success-subtle border border-success-subtle">
                            <span class="text-muted small fw-semibold">Estimasi Harga:</span>
                            <span class="fs-4 fw-bold text-success">{{ $laptop->formatted_price }}</span>
                        </div>
                    @endif
                </div>

                <div class="text-end">
                    <span class="badge bg-light text-muted border px-3 py-2">
                        Catalog Ref: #LAP-{{ str_pad($laptop->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Column: Core Hardware & Diagnostics -->
        <div class="col-lg-8">
            
            <!-- LAPTOP PHOTO SHOWCASE (If Available) -->
            @if($laptop->hasImage())
                <div class="card mb-4 shadow-sm border-0 bg-white overflow-hidden">
                    <div class="card-body p-3 text-center bg-light">
                        <img src="{{ $laptop->image_url }}" alt="{{ $laptop->brand }} {{ $laptop->model }}" class="img-fluid rounded shadow-sm" style="max-height: 380px; object-fit: contain;">
                    </div>
                </div>
            @endif

            <!-- SECTION 1: Core Hardware Specifications (REQUIRED - Always Rendered) -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-cpu-fill text-primary fs-5"></i>
                    <h5 class="card-title fw-bold mb-0">1. Core Hardware Specifications</h5>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-auto">Verified Core</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted small fw-semibold text-uppercase">Processor (CPU)</div>
                                <div class="fs-6 fw-bold text-dark mt-1">
                                    <i class="bi bi-cpu text-primary me-1"></i>
                                    {{ $laptop->processor_cpu }}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted small fw-semibold text-uppercase">System Memory (RAM)</div>
                                <div class="fs-6 fw-bold text-dark mt-1">
                                    <i class="bi bi-memory text-info me-1"></i>
                                    {{ $laptop->memory_ram }}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted small fw-semibold text-uppercase">Storage Drive</div>
                                <div class="fs-6 fw-bold text-dark mt-1">
                                    <i class="bi bi-hdd-network text-warning me-1"></i>
                                    {{ $laptop->storage }}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="text-muted small fw-semibold text-uppercase">Graphics (GPU)</div>
                                <div class="fs-6 fw-bold text-dark mt-1">
                                    <i class="bi bi-gpu-card text-danger me-1"></i>
                                    {{ $laptop->graphics_gpu }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <div class="text-muted small fw-semibold text-uppercase">Display / Screen</div>
                                <div class="fs-6 fw-bold text-dark mt-1">
                                    <i class="bi bi-display text-secondary me-1"></i>
                                    {{ $laptop->display }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Hardware Health & Diagnostics (DYNAMIC - Omitted if empty) -->
            @if($laptop->hasHealthDiagnostics())
                <div class="card mb-4 shadow-sm border-start border-4 border-success">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-heart-pulse-fill text-success fs-5"></i>
                        <h5 class="card-title fw-bold mb-0">2. Hardware Health &amp; Diagnostics</h5>
                        <span class="badge bg-success-subtle text-success border border-success-subtle ms-auto">Condition Verified</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="list-group list-group-flush">
                            @if(filled($laptop->battery_health))
                                <div class="list-group-item px-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                            <i class="bi bi-battery-charging text-success fs-5"></i>
                                            Battery Health &amp; Capacity
                                        </div>
                                        <small class="text-muted">Daya tahan baterai & kondisi pengisian</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fs-6">
                                            {{ $laptop->battery_health }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if(filled($laptop->storage_health))
                                <div class="list-group-item px-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                            <i class="bi bi-hdd text-info fs-5"></i>
                                            Storage Drive Health (SMART)
                                        </div>
                                        <small class="text-muted">Pemeriksaan integritas baca/tulis drive</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 fs-6">
                                            {{ $laptop->storage_health }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            @if(filled($laptop->thermal_performance))
                                <div class="list-group-item px-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                            <i class="bi bi-thermometer-half text-warning fs-5"></i>
                                            Thermal &amp; Cooling Performance
                                        </div>
                                        <small class="text-muted">Kebisingan kipas, throttling & stabilitas termal saat digunakan</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 fs-6">
                                            {{ $laptop->thermal_performance }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- SECTION 3: Physical & Aesthetic Condition (DYNAMIC - Omitted if empty) -->
            @if($laptop->hasPhysicalCondition())
                <div class="card mb-4 shadow-sm border-start border-4 border-info">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-shield-shaded text-info fs-5"></i>
                        <h5 class="card-title fw-bold mb-0">3. Physical &amp; Aesthetic Condition</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="p-3 bg-light rounded-3 border-start border-3 border-primary">
                            <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $laptop->physical_condition }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- SECTION 4: I/O & Connectivity (DYNAMIC - Omitted if all empty) -->
            @if($laptop->hasIOConnectivity())
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-plug-fill text-dark fs-5"></i>
                        <h5 class="card-title fw-bold mb-0">4. Input / Output &amp; Peripherals</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @if(filled($laptop->ports_connectivity))
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="text-muted small fw-semibold text-uppercase mb-1">Ports &amp; Wireless Connectivity</div>
                                        <div class="text-dark" style="white-space: pre-line;">{{ $laptop->ports_connectivity }}</div>
                                    </div>
                                </div>
                            @endif

                            @if(filled($laptop->camera))
                                <div class="col-sm-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="text-muted small fw-semibold text-uppercase mb-1">
                                            <i class="bi bi-camera-video me-1"></i> Camera / Webcam
                                        </div>
                                        <div class="fw-bold text-dark">{{ $laptop->camera }}</div>
                                    </div>
                                </div>
                            @endif

                            @if(filled($laptop->microphone))
                                <div class="col-sm-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="text-muted small fw-semibold text-uppercase mb-1">
                                            <i class="bi bi-mic me-1"></i> Microphone
                                        </div>
                                        <div class="fw-bold text-dark">{{ $laptop->microphone }}</div>
                                    </div>
                                </div>
                            @endif

                            @if(filled($laptop->audio))
                                <div class="col-sm-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="text-muted small fw-semibold text-uppercase mb-1">
                                            <i class="bi bi-speaker me-1"></i> Audio / Speakers
                                        </div>
                                        <div class="fw-bold text-dark">{{ $laptop->audio }}</div>
                                    </div>
                                </div>
                            @endif

                            @if(filled($laptop->keyboard))
                                <div class="col-sm-6">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="text-muted small fw-semibold text-uppercase mb-1">
                                            <i class="bi bi-keyboard me-1"></i> Keyboard &amp; Trackpad
                                        </div>
                                        <div class="fw-bold text-dark">{{ $laptop->keyboard }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Sidebar Column: Software, Addons, and Actions -->
        <div class="col-lg-4">

            <!-- SECTION 5: Software & Licensing Status (DYNAMIC - Omitted if empty) -->
            @if($laptop->hasSoftwareLicensing())
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-code-square text-dark fs-5"></i>
                        <h6 class="card-title fw-bold mb-0">Software &amp; Licensing</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-unstyled mb-0">
                            @if(filled($laptop->operating_system))
                                <li class="spec-item">
                                    <span class="text-muted small d-block">Operating System</span>
                                    <span class="fw-bold text-dark">
                                        <i class="bi bi-window me-1 text-primary"></i>
                                        {{ $laptop->operating_system }}
                                    </span>
                                </li>
                            @endif

                            @if(!is_null($laptop->is_locks_cleared))
                                <li class="spec-item">
                                    <span class="text-muted small d-block">BIOS / MDM / iCloud Locks</span>
                                    @if($laptop->is_locks_cleared)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle mt-1">
                                            <i class="bi bi-shield-check me-1"></i> Cleared &amp; Unlocked
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle mt-1">
                                            <i class="bi bi-shield-exclamation me-1"></i> Lock Active / Restricted
                                        </span>
                                    @endif
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif

            <!-- SECTION 6: Addons & Included Items (DYNAMIC - Omitted if empty) -->
            @if($laptop->hasAddons())
                <div class="card mb-4 shadow-sm border-start border-4 border-warning">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-box-seam-fill text-warning fs-5"></i>
                        <h6 class="card-title fw-bold mb-0">Included Addons &amp; Accessories</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-gift text-warning fs-5 mt-1"></i>
                            <div class="text-dark fw-medium">{{ $laptop->addons }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Action Card (Admin Only) -->
            @auth
                <div class="card shadow-sm bg-light border-0">
                    <div class="card-body p-4 text-center">
                        <h6 class="fw-bold text-dark mb-2">Admin Quick Actions</h6>
                        <p class="text-secondary small mb-3">
                            Perbarui spesifikasi, tambahkan hasil diagnostik, atau ubah status ketersediaan kapan saja.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('laptops.edit', $laptop) }}" class="btn btn-warning fw-bold">
                                <i class="bi bi-pencil-square me-1"></i> Edit Laptop Details
                            </a>
                            <form action="{{ route('laptops.toggle', $laptop) }}" method="POST" class="d-grid">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-dark btn-sm">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Mark as {{ $laptop->is_available ? 'Reserved / Sold' : 'Available' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth

        </div>
    </div>
</div>
@endsection
