@extends('layouts.app')

@section('title', "{$laptop->brand} {$laptop->model} - Specifications Catalog")

@section('content')
<div class="container py-2">

    {{-- ===== BREADCRUMB & TOP BAR ===== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none">Catalog</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $laptop->model }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            @auth
                <a href="{{ route('laptops.edit', $laptop) }}" class="btn btn-dark btn-sm">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
            @endauth
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm d-none d-md-inline-block">
                <i class="bi bi-printer me-1"></i> Print
            </button>
        </div>
    </div>

    {{-- ===== PRODUCT TITLE CARD ===== --}}
    <div class="card mb-3 shadow-sm border-0 bg-white">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-start gap-2">
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <span class="badge bg-dark px-2 py-1">{{ $laptop->brand }}</span>
                        @if($laptop->is_available)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i> Available
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill">
                                <i class="bi bi-dash-circle-fill me-1"></i> Reserved / Unavailable
                            </span>
                        @endif
                        <span class="badge bg-light text-muted border ms-auto px-2 py-1 small">
                            #LAP-{{ str_pad($laptop->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    <h1 class="h3 fw-bold text-dark mb-1">{{ $laptop->brand }} {{ $laptop->model }}</h1>
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <span class="text-secondary small">Spesifikasi Hardware &amp; Penilaian Diagnostik</span>
                        @if($laptop->hasPriceEstimate())
                            <div class="d-inline-flex align-items-baseline gap-1 px-2 py-1 rounded-2 bg-success-subtle border border-success-subtle">
                                <span class="text-muted small fw-semibold">Estimasi:</span>
                                <span class="fs-5 fw-bold text-success lh-1">{{ $laptop->formatted_price }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TWO-COLUMN LAYOUT ===== --}}
    {{-- On mobile: main column (order-1) → physical condition (order-2) → rest of sidebar (order-3)
         On lg+:   main column left (8/12) | sidebar right (4/12) sticky              --}}
    <div class="row g-3">

        {{-- ===== MAIN COLUMN ===== --}}
        <div class="col-lg-8 order-1">

            {{-- LAPTOP PHOTO SHOWCASE (Single Photo or Carousel for Multiple) --}}
            @if($laptop->hasImage())
                <div class="card mb-3 shadow-sm border-0 bg-white overflow-hidden">
                    <div class="card-body p-2 text-center bg-light">
                        @if($laptop->hasMultipleImages())
                            <div id="laptopPhotoCarousel" class="carousel slide" data-bs-ride="false">
                                <!-- Indicators -->
                                <div class="carousel-indicators mb-2">
                                    @foreach($laptop->all_image_urls as $idx => $url)
                                        <button type="button" 
                                                data-bs-target="#laptopPhotoCarousel" 
                                                data-bs-slide-to="{{ $idx }}" 
                                                class="{{ $idx === 0 ? 'active' : '' }}" 
                                                aria-current="{{ $idx === 0 ? 'true' : 'false' }}" 
                                                aria-label="Photo {{ $idx + 1 }}"></button>
                                    @endforeach
                                </div>

                                <!-- Carousel Items -->
                                <div class="carousel-inner rounded">
                                    @foreach($laptop->all_image_urls as $idx => $url)
                                        <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}">
                                            <img src="{{ $url }}"
                                                 alt="{{ $laptop->brand }} {{ $laptop->model }} - Photo {{ $idx + 1 }}"
                                                 class="img-fluid rounded shadow-sm d-inline-block"
                                                 style="max-height: 340px; width: auto; object-fit: contain;">
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Prev/Next Controls -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#laptopPhotoCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon p-3 bg-dark bg-opacity-75 rounded-circle shadow-sm" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#laptopPhotoCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon p-3 bg-dark bg-opacity-75 rounded-circle shadow-sm" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        @else
                            <img src="{{ $laptop->image_url }}"
                                 alt="{{ $laptop->brand }} {{ $laptop->model }}"
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 300px; object-fit: contain;">
                        @endif
                    </div>
                </div>
            @endif

            {{-- ===== SECTION 1: Core Hardware Specs ===== --}}
            <div class="card mb-3 shadow-sm border-start border-4 border-primary">
                <div class="card-header bg-white py-2 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-cpu-fill text-primary"></i>
                    <h6 class="fw-bold mb-0">1. Core Hardware Specifications</h6>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-auto small">Verified Core</span>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-6 col-md-4">
                            <div class="spec-cell">
                                <div class="spec-label"><i class="bi bi-cpu text-primary me-1"></i>Processor (CPU)</div>
                                <div class="spec-value">{{ $laptop->processor_cpu }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="spec-cell">
                                <div class="spec-label"><i class="bi bi-memory text-info me-1"></i>Memory (RAM)</div>
                                <div class="spec-value">{{ $laptop->memory_ram }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="spec-cell">
                                <div class="spec-label"><i class="bi bi-hdd-network text-warning me-1"></i>Storage Drive</div>
                                <div class="spec-value">{{ $laptop->storage }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="spec-cell">
                                <div class="spec-label"><i class="bi bi-gpu-card text-danger me-1"></i>Graphics (GPU)</div>
                                <div class="spec-value">{{ $laptop->graphics_gpu }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="spec-cell">
                                <div class="spec-label"><i class="bi bi-display text-secondary me-1"></i>Display / Screen</div>
                                <div class="spec-value">{{ $laptop->display }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== SECTION 2: Hardware Health & Diagnostics ===== --}}
            @if($laptop->hasHealthDiagnostics())
                <div class="card mb-3 shadow-sm border-start border-4 border-success">
                    <div class="card-header bg-white py-2 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-heart-pulse-fill text-success"></i>
                            <h6 class="fw-bold mb-0">2. Hardware Health &amp; Diagnostics</h6>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle small">Condition Verified</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @if(filled($laptop->battery_health))
                                <div class="list-group-item px-3 py-2 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <i class="bi bi-battery-charging text-success flex-shrink-0"></i>
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-dark small lh-sm">Battery Health &amp; Capacity</div>
                                            <div class="text-muted" style="font-size:.72rem;">Daya tahan &amp; kondisi pengisian</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 diagnostic-badge 
                                        text-start text-sm-center align-self-start align-self-sm-center" style="font-size:.72rem;">
                                        {{ $laptop->battery_health }}
                                    </span>
                                </div>
                            @endif
                            @if(filled($laptop->storage_health))
                                <div class="list-group-item px-3 py-2 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <i class="bi bi-hdd text-info flex-shrink-0"></i>
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-dark small lh-sm">Storage Drive Health (SMART)</div>
                                            <div class="text-muted" style="font-size:.72rem;">Integritas baca/tulis drive</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 diagnostic-badge text-start text-sm-center align-self-start align-self-sm-center">
                                        {{ $laptop->storage_health }}
                                    </span>
                                </div>
                            @endif
                            @if(filled($laptop->thermal_performance))
                                <div class="list-group-item px-3 py-2 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <i class="bi bi-thermometer-half text-warning flex-shrink-0"></i>
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-dark small lh-sm">Thermal &amp; Cooling Performance</div>
                                            <div class="text-muted" style="font-size:.72rem;">Throttling &amp; stabilitas termal</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 diagnostic-badge text-start text-sm-center align-self-start align-self-sm-center">
                                        {{ $laptop->thermal_performance }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- ===== SECTION 3: I/O & Connectivity ===== --}}
            @if($laptop->hasIOConnectivity())
                <div class="card mb-3 shadow-sm border-start border-4 border-secondary">
                    <div class="card-header bg-white py-2 border-bottom d-flex align-items-center gap-2">
                        <i class="bi bi-plug-fill text-secondary"></i>
                        <h6 class="fw-bold mb-0">3. Input / Output &amp; Peripherals</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            @if(filled($laptop->ports_connectivity))
                                <div class="col-12 pb-2 border-bottom">
                                    <div class="text-secondary small fw-semibold text-uppercase mb-1">Ports &amp; Wireless Connectivity</div>
                                    <div class="text-dark small" style="white-space: pre-line;">{{ $laptop->ports_connectivity }}</div>
                                </div>
                            @endif
                            @if(filled($laptop->camera))
                                <div class="col-6 col-md-3">
                                    <div class="text-secondary small fw-semibold text-uppercase mb-1">
                                        <i class="bi bi-camera-video me-1"></i>Camera / Webcam
                                    </div>
                                    <div class="fw-bold text-dark small">{{ $laptop->camera }}</div>
                                </div>
                            @endif
                            @if(filled($laptop->microphone))
                                <div class="col-6 col-md-3">
                                    <div class="text-secondary small fw-semibold text-uppercase mb-1">
                                        <i class="bi bi-mic me-1"></i>Microphone
                                    </div>
                                    <div class="fw-bold text-dark small">{{ $laptop->microphone }}</div>
                                </div>
                            @endif
                            @if(filled($laptop->audio))
                                <div class="col-6 col-md-3">
                                    <div class="text-secondary small fw-semibold text-uppercase mb-1">
                                        <i class="bi bi-speaker me-1"></i>Audio / Speakers
                                    </div>
                                    <div class="fw-bold text-dark small">{{ $laptop->audio }}</div>
                                </div>
                            @endif
                            @if(filled($laptop->keyboard))
                                <div class="col-6 col-md-3">
                                    <div class="text-secondary small fw-semibold text-uppercase mb-1">
                                        <i class="bi bi-keyboard me-1"></i>Keyboard &amp; Trackpad
                                    </div>
                                    <div class="fw-bold text-dark small">{{ $laptop->keyboard }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>{{-- /col-lg-8 --}}

        {{-- ===== SIDEBAR COLUMN ===== --}}
        {{-- Mobile: order-2 for Physical Condition (shows right after main on mobile),
                     order-3 for the rest of sidebar cards.
             Desktop (lg+): entire sidebar stacks naturally in col-lg-4. --}}
        <div class="col-lg-4 order-2 order-lg-2">
            <div class="sticky-lg-top" style="top: 4.5rem; z-index: 10;">

                {{-- ===== SECTION 4: Physical & Aesthetic Condition ===== --}}
                @if($laptop->hasPhysicalCondition())
                    <div class="card mb-3 shadow-sm border-start border-4 border-info">
                        <div class="card-header bg-white py-2 border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-shield-shaded text-info"></i>
                            <h6 class="fw-bold mb-0">Physical &amp; Aesthetic Condition</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="spec-cell">
                                <p class="mb-0 small" style="white-space: pre-line; line-height: 1.6;">{{ $laptop->physical_condition }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ===== SECTION 5: Software & Licensing ===== --}}
                @if($laptop->hasSoftwareLicensing())
                    <div class="card mb-3 shadow-sm border-start border-4 border-dark">
                        <div class="card-header bg-white py-2 border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-code-square text-dark"></i>
                            <h6 class="fw-bold mb-0">Software &amp; Licensing</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2">
                                @if(filled($laptop->operating_system))
                                    <div class="spec-cell">
                                        <div class="spec-label">Operating System</div>
                                        <div class="spec-value small">
                                            <i class="bi bi-window me-1 text-primary"></i>{{ $laptop->operating_system }}
                                        </div>
                                    </div>
                                @endif
                                @if(!is_null($laptop->is_locks_cleared))
                                    <div class="spec-cell">
                                        <div class="spec-label">BIOS / MDM / iCloud Locks</div>
                                        <div class="mt-1">
                                            @if($laptop->is_locks_cleared)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle small">
                                                    <i class="bi bi-shield-check me-1"></i> Cleared &amp; Unlocked
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle small">
                                                    <i class="bi bi-shield-exclamation me-1"></i> Lock Active / Restricted
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ===== SECTION 6: Addons & Accessories ===== --}}
                @if($laptop->hasAddons())
                    <div class="card mb-3 shadow-sm border-start border-4 border-warning">
                        <div class="card-header bg-white py-2 border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-box-seam-fill text-warning"></i>
                            <h6 class="fw-bold mb-0">Included Addons &amp; Accessories</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="spec-cell d-flex align-items-start gap-2">
                                <i class="bi bi-gift text-warning flex-shrink-0"></i>
                                <div class="spec-value small fw-medium">{{ $laptop->addons }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Admin Quick Actions --}}
                @auth
                    <div class="card shadow-sm border-0 bg-light">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-1">Admin Quick Actions</h6>
                            <p class="text-secondary small mb-3">
                                Perbarui spesifikasi, diagnostik, atau ubah status ketersediaan.
                            </p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('laptops.edit', $laptop) }}" class="btn btn-warning btn-sm fw-bold">
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
                
            </div>{{-- /sticky-lg-top --}}
        </div>{{-- /col-lg-4 --}}

    </div>{{-- /row --}}
</div>

@push('styles')
<style>
    /* ---- Spec Tiles (Light background high-contrast tiles) ---- */
    .spec-cell {
        padding: .5rem .625rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: .375rem;
        height: 100%;
    }
    .spec-label {
        font-size: .7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #64748b;
        margin-bottom: .2rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .spec-value {
        font-size: .875rem;
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
    }

    /* ---- Explicit Dark Mode High-Contrast Light Tiles ---- */
    [data-bs-theme="dark"] .spec-cell {
        background-color: #f1f5f9 !important;
        border: 1px solid #cbd5e1 !important;
    }
    [data-bs-theme="dark"] .spec-cell .spec-label {
        color: #475569 !important;
    }
    [data-bs-theme="dark"] .spec-cell .spec-value,
    [data-bs-theme="dark"] .spec-cell p,
    [data-bs-theme="dark"] .spec-cell span:not(.badge),
    [data-bs-theme="dark"] .spec-cell div:not(.badge) {
        color: #0f172a !important;
    }
    /* Ensure readable saturation for icons inside light tiles in dark mode */
    [data-bs-theme="dark"] .spec-cell .text-primary {
        color: #0d6efd !important;
    }
    [data-bs-theme="dark"] .spec-cell .text-info {
        color: #087990 !important;
    }
    [data-bs-theme="dark"] .spec-cell .text-warning {
        color: #b45309 !important;
    }
    [data-bs-theme="dark"] .spec-cell .text-danger {
        color: #dc3545 !important;
    }
    [data-bs-theme="dark"] .spec-cell .text-secondary {
        color: #475569 !important;
    }

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

    /* ---- Sticky sidebar: lower z-index so it never covers navbar (1020) or dropdowns (1000) ---- */
.sticky-lg-top {
    z-index: 10 !important;
}

/* ---- Mobile: sidebar stacks below main, no sticky ---- */
    @media (max-width: 991.98px) {
        .sticky-lg-top {
            position: static !important;
        }
    }
</style>
@endpush
@endsection
