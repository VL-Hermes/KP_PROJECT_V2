@extends('layouts.app')

@section('title', 'Inventory Management Dashboard')

@section('content')
<div class="container-fluid px-3 px-lg-4 px-xl-5 py-2">
    <!-- Dashboard Header & Stats -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Inventory Management Dashboard</h1>
            <p class="text-secondary small mb-0">Kelola spesifikasi laptop bekas, ketersediaan, dan catatan kesehatan hardware</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-eye me-1"></i> Public Catalog
            </a>
            <a href="{{ route('laptops.create') }}" class="btn btn-warning fw-bold shadow-sm">
                <i class="bi bi-plus-circle-fill me-1"></i> Add New Laptop
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Laptops</div>
                        <div class="h3 fw-bold text-dark mb-0 mt-1">{{ $totalCount }}</div>
                    </div>
                    <div class="rounded-circle bg-primary-subtle p-3 text-primary">
                        <i class="bi bi-laptop fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Available for Sale</div>
                        <div class="h3 fw-bold text-success mb-0 mt-1">{{ $availableCount }}</div>
                    </div>
                    <div class="rounded-circle bg-success-subtle p-3 text-success">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm bg-white p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Reserved / Sold</div>
                        <div class="h3 fw-bold text-secondary mb-0 mt-1">{{ $unavailableCount }}</div>
                    </div>
                    <div class="rounded-circle bg-secondary-subtle p-3 text-secondary">
                        <i class="bi bi-dash-circle-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search Toolbar -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form id="inventory-filter-form" action="{{ route('laptops.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i id="inventory-search-icon" class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" id="inventory-search" class="form-control border-start-0" 
                               placeholder="Search brand, model, processor, storage..." 
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <select name="brand" id="inventory-brand" class="form-select">
                        <option value="">All Brands ({{ $totalCount }})</option>
                        @foreach($brands as $brand)
                            @php
                                $bName = is_object($brand) ? $brand->brand : $brand;
                                $bCount = is_object($brand) ? $brand->count : null;
                            @endphp
                            <option value="{{ $bName }}" {{ request('brand') === $bName ? 'selected' : '' }}>
                                {{ $bName }} @if($bCount !== null) ({{ $bCount }}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6 col-md-2">
                    <select name="status" id="inventory-status" class="form-select">
                        <option value="">All Statuses ({{ $totalCount }})</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>
                            Available ({{ $availableCount }})
                        </option>
                        <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>
                            Reserved / Sold ({{ $unavailableCount }})
                        </option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2" id="inventory-actions-col">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('laptops.index') }}" id="inventory-reset-btn" class="btn btn-outline-secondary {{ request()->hasAny(['search', 'brand', 'status']) ? '' : 'd-none' }}" title="Reset" data-ajax-link>
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table Container (Updated via AJAX) -->
    <div id="inventory-table-container">

    <!-- Inventory Table -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-3 py-3" style="width: 70px;">ID</th>
                        <th scope="col" class="py-3">Brand & Model</th>
                        <th scope="col" class="py-3">Core Specs (CPU / RAM / Storage)</th>
                        <th scope="col" class="py-3">Display & GPU</th>
                        <th scope="col" class="py-3 text-center" style="min-width: 120px;">Date Added</th>
                        <th scope="col" class="py-3 text-center">Status</th>
                        <th scope="col" class="py-3 text-end pe-3" style="min-width: 170px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laptops as $laptop)
                        <tr class="align-middle laptop-row" data-href="{{ route('catalog.show', $laptop) }}">
                            <td class="ps-3 fw-bold text-muted small">
                                #{{ $laptop->id }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded bg-light border text-center overflow-hidden d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                        @if($laptop->hasImage())
                                            <img src="{{ $laptop->image_url }}" alt="{{ $laptop->brand }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="bi bi-laptop text-primary fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-1 flex-wrap">
                                            <span class="badge bg-dark-subtle text-dark border small">{{ $laptop->brand }}</span>
                                            @if($laptop->hasPriceEstimate())
                                                <span class="badge bg-success-subtle text-success border border-success-subtle small fw-bold">{{ $laptop->formatted_price }}</span>
                                            @endif
                                        </div>
                                        <div class="fw-bold text-dark">{{ $laptop->model }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div class="fw-semibold text-dark"><i class="bi bi-cpu text-primary me-1"></i>{{ $laptop->processor_cpu }}</div>
                                    <div class="text-muted"><i class="bi bi-memory text-info me-1"></i>{{ $laptop->memory_ram }} &bull; <i class="bi bi-hdd text-warning me-1"></i>{{ $laptop->storage }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="small text-secondary">
                                    <div><i class="bi bi-display me-1"></i>{{ $laptop->display }}</div>
                                    <div class="text-truncate" style="max-width: 180px;"><i class="bi bi-gpu-card me-1 text-danger"></i>{{ $laptop->graphics_gpu }}</div>
                                </div>
                            </td>
                            <td class="text-center text-nowrap">
                                <span class="small text-secondary" title="{{ $laptop->created_at ? $laptop->created_at->format('Y-m-d') : '' }}">
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>{{ $laptop->created_at ? $laptop->created_at->format('d M Y') : '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('laptops.toggle', $laptop) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($laptop->is_available)
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold" title="Click to mark as Sold/Unavailable">
                                            <i class="bi bi-check-circle-fill me-1"></i> Available
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold" title="Click to mark as Available">
                                            <i class="bi bi-dash-circle me-1"></i> Sold / Reserved
                                        </button>                                    
                                    @endif
                                </form>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('catalog.show', $laptop) }}" class="btn btn-outline-secondary" title="View Public Catalog Page">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('laptops.edit', $laptop) }}" class="btn btn-outline-primary" title="Edit Specifications">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Delete from Inventory" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            data-action="{{ route('laptops.destroy', $laptop) }}"
                                            data-name="{{ $laptop->brand }} {{ $laptop->model }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-3">
                                    <i class="bi bi-laptop text-muted" style="font-size: 3rem;"></i>
                                    <h5 class="fw-bold mt-2">No Laptops Found</h5>
                                    <p class="text-muted small">Tidak ada laptop yang sesuai dengan pencarian, atau inventaris masih kosong.</p>
                                    <a href="{{ route('laptops.create') }}" class="btn btn-warning btn-sm fw-bold mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Add First Laptop
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($laptops->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $laptops->links() }}
            </div>
        @endif
        </div>
    </div>{{-- /#inventory-table-container --}}
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirm Laptop Removal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="mb-1">Apakah Anda yakin ingin menghapus laptop ini secara permanen dari katalog inventaris?</p>
                <div class="alert alert-light border mt-2 mb-0 fw-bold text-dark" id="deleteLaptopName">
                    <!-- Dynamic laptop name -->
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill me-1"></i> Delete Laptop
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Delete Confirmation Modal setup
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const actionUrl = button.getAttribute('data-action');
                const laptopName = button.getAttribute('data-name');

                const form = document.getElementById('deleteForm');
                const nameDisplay = document.getElementById('deleteLaptopName');

                form.setAttribute('action', actionUrl);
                nameDisplay.textContent = laptopName;
            });
        }

        // 2. Seamless AJAX Search & Filter for Inventory Management
        const filterForm = document.getElementById('inventory-filter-form');
        const tableContainer = document.getElementById('inventory-table-container');
        const searchInput = document.getElementById('inventory-search');
        const searchIcon = document.getElementById('inventory-search-icon');
        const resetBtn = document.getElementById('inventory-reset-btn');

        if (!filterForm || !tableContainer) return;

        let activeController = null;

        async function fetchInventory(url, pushState = true) {
            if (activeController) {
                activeController.abort();
            }
            activeController = new AbortController();

            // Dim table container for visual feedback
            tableContainer.style.opacity = '0.5';
            tableContainer.style.pointerEvents = 'none';
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

                // Update Table Content
                const newTable = doc.getElementById('inventory-table-container');
                if (newTable) {
                    tableContainer.innerHTML = newTable.innerHTML;
                }

                // Update Form inputs if needed (when resetting or clicking back)
                const newSearch = doc.getElementById('inventory-search');
                if (newSearch && searchInput && document.activeElement !== searchInput) {
                    searchInput.value = newSearch.value;
                }

                const newBrand = doc.getElementById('inventory-brand');
                const brand = document.getElementById('inventory-brand');
                if (newBrand && brand) {
                    brand.innerHTML = newBrand.innerHTML;
                    brand.value = newBrand.value;
                }

                const newStatus = doc.getElementById('inventory-status');
                const status = document.getElementById('inventory-status');
                if (newStatus && status) {
                    status.innerHTML = newStatus.innerHTML;
                    status.value = newStatus.value;
                }

                // Update Reset button visibility
                const newReset = doc.getElementById('inventory-reset-btn');
                if (newReset && resetBtn) {
                    resetBtn.className = newReset.className;
                }

                // Update browser URL without full reload
                if (pushState) {
                    window.history.pushState({ path: url }, '', url);
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('AJAX Inventory load error:', err);
                }
            } finally {
                tableContainer.style.opacity = '1';
                tableContainer.style.pointerEvents = 'auto';
                if (searchIcon) {
                    searchIcon.className = 'bi bi-search';
                }
            }
        }

        // Form Submit via AJAX
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            const url = `${filterForm.action}?${params.toString()}`;
            fetchInventory(url);
        });

        // Dropdown filters trigger fetch automatically on change
        const filterSelects = filterForm.querySelectorAll('select');
        filterSelects.forEach(select => {
            select.addEventListener('change', function () {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `${filterForm.action}?${params.toString()}`;
                fetchInventory(url);
            });
        });

        // Delegated clicks for pagination & reset links
        document.addEventListener('click', function (e) {
            const link = e.target.closest('#inventory-table-container .pagination a, #inventory-reset-btn');
            if (link && link.href) {
                e.preventDefault();
                fetchInventory(link.href);
            }
        });

        // Browser back/forward navigation
        window.addEventListener('popstate', function () {
            fetchInventory(window.location.href, false);
        });

        // 3. Clickable Table Rows -> Navigate to Catalog Detail Page
        document.addEventListener('click', function (e) {
            // Ignore clicks on interactive controls inside the row
            if (e.target.closest('button, a, input, select, textarea, form, [data-bs-toggle]')) {
                return;
            }

            const row = e.target.closest('tr[data-href]');
            if (row && row.dataset.href) {
                if (e.ctrlKey || e.metaKey) {
                    window.open(row.dataset.href, '_blank');
                } else {
                    window.location.href = row.dataset.href;
                }
            }
        });

        // Middle-click opens in new tab
        document.addEventListener('auxclick', function (e) {
            if (e.button === 1) {
                if (e.target.closest('button, a, input, select, textarea, form, [data-bs-toggle]')) {
                    return;
                }
                const row = e.target.closest('tr[data-href]');
                if (row && row.dataset.href) {
                    window.open(row.dataset.href, '_blank');
                }
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
/* Clickable laptop table row styling */
.laptop-row {
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}
.laptop-row:hover > td {
    background-color: rgba(13, 110, 253, 0.04) !important;
}
[data-bs-theme="dark"] .laptop-row:hover > td {
    background-color: rgba(255, 255, 255, 0.05) !important;
}
</style>
@endpush
@endsection

