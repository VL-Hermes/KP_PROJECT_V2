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
                <div class="d-flex flex-wrap gap-2 text-muted">
                    <span class="badge bg-light text-dark border"><i class="bi bi-check2 text-success me-1"></i> Spesifikasi Transparan</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-check2 text-success me-1"></i> Laporan Diagnostik Hardware</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-check2 text-success me-1"></i> Info Nyata, Tanpa Rekayasa</span>
                </div>
            </div>
            {{-- <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="card bg-light border-0 p-3 text-start">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small">Total Inventaris:</span>
                        <span class="badge bg-secondary">{{ $totalAll }} Unit</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Tersedia untuk Dijual:</span>
                        <span class="badge bg-success">{{ $totalAvailable }} Tersedia</span>
                    </div>
                    @auth
                        <a href="{{ route('laptops.create') }}" class="btn btn-outline-dark btn-sm w-100 mt-1">
                            <i class="bi bi-plus-circle me-1"></i> List New Laptop
                        </a>
                    @endauth
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Search & Filter Controls (Instant Auto-Filter) -->
    <div class="card shadow-sm border-0 mb-4 bg-white">
        <div class="card-body p-3">
            <form id="catalog-filter-form" action="{{ route('catalog.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Search Keyword -->
                <div class="{{ (request()->filled('search') || request()->filled('brand')) ? 'col-12 col-md-7 col-lg-8' : 'col-12 col-md-8 col-lg-9' }}">
                    <div class="input-group">
                        <span class="input-group-text text-muted border-end-0">
                            <i id="search-icon" class="bi bi-search"></i>
                        </span>
                        <input type="search" 
                               name="search" 
                               id="catalog-search"
                               class="form-control border-start-0 border-end-0" 
                               placeholder="Cari merek, model, CPU, RAM, storage..." 
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <button type="submit" class="btn btn-outline-secondary px-3" title="Search">
                            Cari
                        </button>
                    </div>
                </div>

                <!-- Brand Filter (Available Brands Only) -->
                <div class="{{ (request()->filled('search') || request()->filled('brand')) ? 'col-12 col-md-4 col-lg-3' : 'col-12 col-md-4 col-lg-3' }}">
                    <div class="input-group">
                        <span class="input-group-text text-muted border-end-0 d-flex align-items-center">
                            <i class="bi bi-tag d-flex align-items-center"></i>
                        </span>
                        <select name="brand" id="catalog-brand" class="form-select border-start-0 auto-submit">
                            <option value="">Semua Merek ({{ $brands->sum('count') }})</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->brand }}" {{ request('brand') === $brand->brand ? 'selected' : '' }}>
                                    {{ $brand->brand }} ({{ $brand->count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Reset Button (Visible when any filter is active) -->
                <div id="catalog-reset-col" class="{{ (request()->filled('search') || request()->filled('brand')) ? 'col-12 col-md-1 col-lg-1 d-flex align-items-center' : 'd-none' }}">
                    <a href="{{ route('catalog.index') }}" id="catalog-reset-btn" class="btn btn-outline-secondary w-100 px-2" title="Reset all filters back to default" data-ajax-link>
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Filters Summary Badge Pills -->
    <div id="catalog-active-filters">
        @if(request()->filled('search') || request()->filled('brand'))
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
                <a href="{{ route('catalog.index') }}" class="text-danger text-decoration-none small ms-1" data-ajax-link>
                    <i class="bi bi-trash3 me-1"></i>Clear all
                </a>
            </div>
        @endif
    </div>

    <!-- Catalog Content Container (Updated via AJAX) -->
    <div id="catalog-content-container">

    <!-- Catalog Laptop List -->
    @if($laptops->count() > 0)
        <div class="row row-cols-1 row-cols-xl-2 g-3 mb-4">
            @foreach($laptops as $laptop)
                <div class="col">
                <div class="card shadow-sm border-0 hover-shadow h-100">
                    <div class="card-body p-0">
                        <div class="row g-0 align-items-stretch h-100">

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
                                        <div class="fw-bold text-body-secondary fs-6">
                                            RP -----
                                        </div>
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
                            <div class="col-12 col-md p-3 border-bottom border-md-bottom-0 border-md-end    ">
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
                            <div class="col-12 col-md-auto d-flex flex-column justify-content-between p-3" style="min-width:200px; max-width:260px;">
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
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary" data-ajax-link>
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
    </div>{{-- /#catalog-content-container --}}
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

    /* Unified input-group focus style */
    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.2);
        border-radius: var(--bs-border-radius);
    }
    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-select,
    .input-group:focus-within .form-control {
        border-color: #86b7fe !important;
    }
    .input-group .form-select:focus,
    .input-group .form-control:focus {
        box-shadow: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
    /**
     * Seamless AJAX Search, Filter & Pagination for Public Catalog
     * Updates the catalog content and active filter badges without full page reloads.
     */
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('catalog-filter-form');
        const contentContainer = document.getElementById('catalog-content-container');
        const activeFiltersContainer = document.getElementById('catalog-active-filters');
        const searchInput = document.getElementById('catalog-search');
        const searchIcon = document.getElementById('search-icon');
        const resetCol = document.getElementById('catalog-reset-col');

        if (!filterForm || !contentContainer) return;

        let activeController = null;

        async function fetchCatalog(url, pushState = true) {
            if (activeController) {
                activeController.abort();
            }
            activeController = new AbortController();

            // Visual feedback: dim container & show spinner in search icon
            contentContainer.style.opacity = '0.5';
            contentContainer.style.pointerEvents = 'none';
            if (searchIcon) {
                searchIcon.className = 'spinner-border spinner-border-sm text-primary';
            }

            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: activeController.signal
                });

                if (!response.ok) throw new Error('Network response error');

                const htmlText = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(htmlText, 'text/html');

                // 1. Replace catalog cards / pagination content
                const newContent = doc.getElementById('catalog-content-container');
                if (newContent) {
                    contentContainer.innerHTML = newContent.innerHTML;
                }

                // 2. Replace active filters badges
                const newActiveFilters = doc.getElementById('catalog-active-filters');
                if (newActiveFilters && activeFiltersContainer) {
                    activeFiltersContainer.innerHTML = newActiveFilters.innerHTML;
                }

                // 3. Update form controls (Brand select, Availability select, Search value, Reset button)
                const newSearchInput = doc.getElementById('catalog-search');
                if (newSearchInput && searchInput && document.activeElement !== searchInput) {
                    searchInput.value = newSearchInput.value;
                }

                const newBrandSelect = doc.getElementById('catalog-brand');
                const brandSelect = document.getElementById('catalog-brand');
                if (newBrandSelect && brandSelect) {
                    brandSelect.innerHTML = newBrandSelect.innerHTML;
                    brandSelect.value = newBrandSelect.value;
                }

                // Toggle reset button column
                const newResetCol = doc.getElementById('catalog-reset-col');
                if (newResetCol && resetCol) {
                    resetCol.className = newResetCol.className;
                }

                // Update browser URL without reload
                if (pushState) {
                    window.history.pushState({ path: url }, '', url);
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('AJAX Catalog load error:', err);
                }
            } finally {
                contentContainer.style.opacity = '1';
                contentContainer.style.pointerEvents = 'auto';
                if (searchIcon) {
                    searchIcon.className = 'bi bi-search';
                }
            }
        }

        // 1. Submit search form via AJAX
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            const url = `${filterForm.action}?${params.toString()}`;
            fetchCatalog(url);
        });

        // 2. Dropdown selects: fetch instantly on change
        const autoSelects = filterForm.querySelectorAll('.auto-submit');
        autoSelects.forEach(select => {
            select.addEventListener('change', function () {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `${filterForm.action}?${params.toString()}`;
                fetchCatalog(url);
            });
        });

        // 3. Clearing search input with native 'x'
        if (searchInput) {
            searchInput.addEventListener('search', function () {
                if (this.value === '') {
                    const formData = new FormData(filterForm);
                    const params = new URLSearchParams(formData);
                    const url = `${filterForm.action}?${params.toString()}`;
                    fetchCatalog(url);
                }
            });
        }

        // 4. Delegated clicks for pagination & reset links inside catalog
        document.addEventListener('click', function (e) {
            // Check if pagination link or data-ajax-link was clicked
            const link = e.target.closest('#catalog-content-container .pagination a, #catalog-active-filters a, a[data-ajax-link]');
            if (link && link.href) {
                e.preventDefault();
                fetchCatalog(link.href);
            }
        });

        // 5. Handle browser back / forward buttons
        window.addEventListener('popstate', function (e) {
            fetchCatalog(window.location.href, false);
        });
    });
</script>
@endpush
@endsection
