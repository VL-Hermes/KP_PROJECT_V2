@extends('layouts.app')

@section('title', 'Used Laptop Specifications Catalog')

@section('content')
<div class="container-fluid px-3 px-md-4 px-xl-5">
    <!-- Hero / Catalog Banner -->
    <div class="p-4 p-md-5 mb-4 rounded-3 bg-white border shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 mb-2 rounded-pill fw-semibold">
                    <i class="bi bi-shield-check me-1"></i> Verified Specifications
                </span>
                <h1 class="display-6 fw-bold text-dark mb-2">Used Laptop Catalog</h1>
                <p class="lead text-secondary mb-3">
                    Temukan spesifikasi lengkap, hasil diagnostik hardware terverifikasi, dan laporan kondisi fisik nyata dari laptop bekas yang tersedia.
                </p>
                <div class="d-flex flex-wrap gap-2 text-muted small">
                    <span class="badge bg-light text-dark border"><i class="bi bi-check2 text-success me-1"></i> Spesifikasi Transparan</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-check2 text-success me-1"></i> Laporan Diagnostik Hardware</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-check2 text-success me-1"></i> Info Nyata, Tanpa Rekayasa</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="card bg-light border-0 p-3 text-start">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small">Total Inventaris:</span>
                        <span class="badge bg-secondary">{{ $totalAll }} Unit</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Tersedia untuk Dijual:</span>
                        <span class="badge bg-success">{{ $totalAvailable }} Tersedia</span>
                    </div>
                    @auth
                        <a href="{{ route('laptops.create') }}" class="btn btn-outline-dark btn-sm w-100 mt-1">
                            <i class="bi bi-plus-circle me-1"></i> List New Laptop
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Controls (Instant Auto-Filter) -->
    <div class="card shadow-sm border-0 mb-4 bg-white">
        <div class="card-body p-3">
            <form id="catalog-filter-form" action="{{ route('catalog.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Search Keyword -->
                <div class="{{ (request()->filled('search') || request()->filled('brand') || (request()->has('availability') && request('availability') !== 'available')) ? 'col-12 col-md-5 col-lg-5' : 'col-12 col-md-6 col-lg-6' }}">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i id="search-icon" class="bi bi-search"></i>
                        </span>
                        <input type="search" 
                               name="search" 
                               id="catalog-search"
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari merek, model, CPU, RAM, storage..." 
                               value="{{ request('search') }}"
                               autocomplete="off">
                    </div>
                </div>

                <!-- Brand Filter -->
                <div class="col-6 col-md-3 col-lg-3">
                    <select name="brand" id="catalog-brand" class="form-select auto-submit">
                        <option value="">Semua Merek ({{ $brands->count() }})</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>
                                {{ $brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Availability Filter -->
                <div class="{{ (request()->filled('search') || request()->filled('brand') || (request()->has('availability') && request('availability') !== 'available')) ? 'col-6 col-md-3 col-lg-3' : 'col-6 col-md-3 col-lg-3' }}">
                    <select name="availability" id="catalog-availability" class="form-select auto-submit">
                        <option value="available" {{ request('availability', 'available') === 'available' ? 'selected' : '' }}>
                            Tersedia Saja
                        </option>
                        <option value="all" {{ request('availability') === 'all' ? 'selected' : '' }}>
                            Semua (Tersedia & Dipesan)
                        </option>
                        <option value="unavailable" {{ request('availability') === 'unavailable' ? 'selected' : '' }}>
                            Dipesan / Terjual Saja
                        </option>
                    </select>
                </div>

                <!-- Reset Button (Visible when any filter is active) -->
                @if(request()->filled('search') || request()->filled('brand') || (request()->has('availability') && request('availability') !== 'available'))
                    <div class="col-12 col-md-1 col-lg-1 d-flex align-items-center">
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100 px-2" title="Reset all filters back to default">
                            <i class="bi bi-x-circle me-1"></i> Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Active Filters Summary Badge Pills -->
    @if(request()->filled('search') || request()->filled('brand') || (request()->has('availability') && request('availability') !== 'available'))
        <div class="d-flex flex-wrap align-items-center gap-2 mb-3 small">
            <span class="text-secondary fw-semibold">Filter aktif:</span>
            @if(request()->filled('search'))
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                    Pencarian: "{{ request('search') }}"
                </span>
            @endif
            @if(request()->filled('brand'))
                <span class="badge bg-dark-subtle text-dark border px-2 py-1">
                    Merek: {{ request('brand') }}
                </span>
            @endif
            @if(request()->has('availability') && request('availability') !== 'available')
                <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                    Status: {{ request('availability') === 'all' ? 'Semua (Tersedia & Dipesan)' : 'Dipesan Saja' }}
                </span>
            @endif
            <a href="{{ route('catalog.index') }}" class="text-danger text-decoration-none small ms-1">
                <i class="bi bi-trash3 me-1"></i>Clear all
            </a>
        </div>
    @endif

    <!-- Catalog Laptop List -->
    @if($laptops->count() > 0)
        <div class="row row-cols-1 row-cols-xl-2 g-3 mb-4">
            @foreach($laptops as $laptop)
                <div class="col">
                <div class="card shadow-sm border-0 hover-shadow h-100">
                    <div class="card-body p-0">
                        <div class="row g-0 align-items-stretch">

                            {{-- Left accent bar --}}
                            <div class="col-auto d-none d-md-block" style="width:5px; background: {{ $laptop->is_available ? '#198754' : '#adb5bd' }}; border-radius: .375rem 0 0 .375rem;"></div>

                            {{-- Photo + Brand + Model + Price --}}
                            <div class="col-12 col-md-4 col-xl-3 d-flex flex-column justify-content-between p-3 border-bottom border-md-bottom-0 border-md-end">
                                <div>
                                    <div class="position-relative mb-2 bg-light rounded text-center border overflow-hidden d-flex align-items-center justify-content-center" style="height: 110px;">
                                        @if($laptop->hasImage())
                                            <img src="{{ $laptop->image_url }}" alt="{{ $laptop->brand }} {{ $laptop->model }}" style="max-height: 100px; max-width: 100%; object-fit: contain;">
                                        @else
                                            <div class="text-secondary opacity-50 py-3">
                                                <i class="bi bi-laptop display-6"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-dark-subtle text-dark border px-2 py-1">
                                                {{ $laptop->brand }}
                                            </span>
                                        </div>
                                    </div>
                                    <h6 class="fw-bold mb-1 lh-sm text-dark">{{ $laptop->model }}</h6>
                                </div>
                                <div class="mt-2 d-flex justify-content-between align-items-center flex-wrap gap-1">
                                    @if($laptop->hasPriceEstimate())
                                        <div class="fw-bold text-success fs-6">
                                            {{ $laptop->formatted_price }}
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic">Hubungi Penjual</span>
                                    @endif

                                    @if($laptop->is_available)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                            <i class="bi bi-check-circle me-1"></i> Available
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                            <i class="bi bi-lock me-1"></i> Reserved
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Core Specs Grid --}}
                            <div class="col-12 col-md p-3">
                                <div class="row g-2 small">
                                    {{-- CPU — full width row --}}
                                    <div class="col-12 d-flex align-items-start gap-2">
                                        <i class="bi bi-cpu text-primary mt-1" style="min-width:1rem;"></i>
                                        <span class="text-dark fw-semibold">{{ $laptop->processor_cpu }}</span>
                                    </div>
                                    {{-- RAM + Storage --}}
                                    <div class="col-6 col-sm-4 col-lg-12 d-flex align-items-start gap-2">
                                        <i class="bi bi-memory text-info mt-1" style="min-width:1rem;"></i>
                                        <span class="text-secondary">{{ $laptop->memory_ram }}</span>
                                    </div>
                                    <div class="col-6 col-sm-4 col-lg-12 d-flex align-items-start gap-2">
                                        <i class="bi bi-device-hdd text-warning mt-1" style="min-width:1rem;"></i>
                                        <span class="text-secondary">{{ $laptop->storage }}</span>
                                    </div>
                                    {{-- GPU --}}
                                    <div class="col-12 col-sm-4 col-lg-12 d-flex align-items-start gap-2">
                                        <i class="bi bi-gpu-card text-danger mt-1" style="min-width:1rem;"></i>
                                        <span class="text-secondary">{{ $laptop->graphics_gpu }}</span>
                                    </div>

                                    {{-- Display --}}
                                    <div class="col-12 d-flex align-items-start gap-2">
                                        <i class="bi bi-display text-secondary mt-1" style="min-width:1rem;"></i>
                                        <span class="text-secondary">{{ $laptop->display }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Right column: Optional badges + condition + actions --}}
                            <div class="col-12 col-md-auto d-flex flex-column justify-content-between p-3 border-top border-md-top-0 border-md-start" style="min-width:200px; max-width:260px;">
                                <div>
                                    {{-- Optional highlight badges --}}
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        @if(filled($laptop->battery_health))
                                            <span class="badge bg-success-subtle text-success-emphasis border d-inline-flex align-items-center" style="font-size:.72rem; max-width:200px;" title="{{ $laptop->battery_health }}">
                                                <i class="bi bi-battery-charging me-1 flex-shrink-0"></i>
                                                <span class="text-truncate">{{ $laptop->battery_health }}</span>
                                            </span>
                                        @endif
                                        @if(filled($laptop->operating_system))
                                            <span class="badge bg-info-subtle text-info-emphasis border d-inline-flex align-items-center" style="font-size:.72rem; max-width:220px;" title="{{ $laptop->operating_system }}">
                                                <i class="bi bi-windows me-1 flex-shrink-0"></i>
                                                <span class="text-truncate">{{ $laptop->operating_system }}</span>
                                            </span>
                                        @endif
                                        @if($laptop->is_locks_cleared === true)
                                            <span class="badge bg-primary-subtle text-primary border" style="font-size:.72rem;">
                                                <i class="bi bi-unlock me-1"></i>Locks Cleared
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Physical condition note --}}
                                    @if(filled($laptop->physical_condition))
                                        <p class="text-muted mb-2 fst-italic" style="font-size:.78rem; line-height:1.4;">
                                            <i class="bi bi-chat-left-quote me-1 text-secondary"></i>{{ $laptop->physical_condition }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Action buttons --}}
                                <div class="d-flex gap-2 mt-2">
                                    <a href="{{ route('catalog.show', $laptop) }}" class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="bi bi-file-text me-1"></i> Full Specs
                                    </a>
                                    @auth
                                        <a href="{{ route('laptops.edit', $laptop) }}" class="btn btn-outline-secondary btn-sm" title="Edit in Inventory">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>

                        </div>{{-- /row --}}
                    </div>
                </div>
                </div>{{-- /col --}}
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $laptops->links() }}
        </div>
    @else
        <div class="card text-center p-5 shadow-sm border-0 bg-white">
            <div class="py-4">
                <i class="bi bi-laptop text-muted" style="font-size: 3.5rem;"></i>
                <h4 class="fw-bold mt-3 text-dark">No Laptops Found</h4>
                <p class="text-secondary max-w-md mx-auto">
                    @if(request()->hasAny(['search', 'brand', 'availability']))
                        Tidak ada laptop yang sesuai dengan filter saat ini. Coba ubah kata kunci atau reset filter.
                    @else
                        Saat ini belum ada laptop bekas yang tercatat dalam katalog inventaris.
                    @endif
                </p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    @if(request()->hasAny(['search', 'brand', 'availability']))
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
                        </a>
                    @endif
                    @auth
                        <a href="{{ route('laptops.create') }}" class="btn btn-warning fw-bold">
                            <i class="bi bi-plus-circle me-1"></i> Add Laptop Now
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    @media (min-width: 768px) {
        .border-md-end { border-right: var(--bs-border-width) solid var(--bs-border-color) !important; }
        .border-md-top-0 { border-top: 0 !important; }
        .border-md-bottom-0 { border-bottom: 0 !important; }
    }
    .hover-shadow { transition: box-shadow .2s ease, transform .15s ease; }
    .hover-shadow:hover { box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.12) !important; transform: translateY(-1px); }
</style>
@endpush

@push('scripts')
<script>
    /**
     * Instant Client-side Filter Behavior for Public Catalog
     * Automatically applies filter on dropdown selection and debounced search typing.
     */
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('catalog-filter-form');
        if (!filterForm) return;

        // 1. Dropdown selects: submit immediately upon change
        const autoSelects = filterForm.querySelectorAll('.auto-submit');
        autoSelects.forEach(select => {
            select.addEventListener('change', function () {
                filterForm.submit();
            });
        });

        // 2. Search input: debounce 400ms so typing waits for the user to pause
        const searchInput = document.getElementById('catalog-search');
        const searchIcon = document.getElementById('search-icon');
        let debounceTimer = null;

        if (searchInput) {
            // If search query is present, keep focus and place cursor at end
            if (searchInput.value && document.activeElement === document.body) {
                searchInput.focus();
                const currentVal = searchInput.value;
                searchInput.value = '';
                searchInput.value = currentVal;
            }

            searchInput.addEventListener('input', function () {
                if (searchIcon) {
                    searchIcon.className = 'spinner-border spinner-border-sm text-primary';
                }
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    filterForm.submit();
                }, 400);
            });

            // If user clicks the native clear 'x' in search field, submit immediately
            searchInput.addEventListener('search', function () {
                filterForm.submit();
            });
        }
    });
</script>
@endpush
@endsection
