@php
    $brands = [
        'Acer','Apple','ASUS','Dell','HP','Huawei',
        'Lenovo','Microsoft','MSI','Samsung','Xiaomi / Redmi',
    ];    
    $currentBrand = old('brand', $laptop->brand);
    $isOther = !empty($currentBrand) && !in_array($currentBrand, $brands);
@endphp

<div class="row g-4">
    <!-- LEFT MAIN COLUMN: Technical Specifications & Testing (col-lg-7 col-xl-8) -->
    <div class="col-lg-7 col-xl-8">
        
        <!-- SECTION 1: Core Hardware Specifications (REQUIRED) -->
        <div class="card shadow-sm border-0 mb-4 border-top border-4 border-primary bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-cpu-fill text-primary fs-5"></i>
                    <h5 class="fw-bold text-dark mb-0">1. Core Hardware Specifications</h5>
                </div>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                    * Required Core Fields
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <!-- Brand -->
                    <div class="col-md-6">
                        <label for="brand" class="form-label fw-bold text-dark">
                            Brand <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('brand') is-invalid @enderror" 
                            id="brand_select" 
                            onchange="toggleOtherBrand(this)" 
                            required>
                        <option value="" disabled {{ empty($currentBrand) ? 'selected' : '' }}>Select a brand...</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}" {{ $currentBrand == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                        @endforeach
                        <option value="Others" {{ $isOther ? 'selected' : '' }}>Others</option>
                    </select>

                    <input type="text" 
                        class="form-control mt-2 @error('brand') is-invalid @enderror {{ $isOther ? '' : 'd-none' }}" 
                        id="brand_input" 
                        name="brand" 
                        placeholder="Enter brand name..."
                        value="{{ $currentBrand }}" 
                        required>

                    <script>
                        function toggleOtherBrand(select) {
                            const input = document.getElementById('brand_input');
                            if (select.value === 'Others') {
                                input.classList.remove('d-none');
                                input.value = '';
                                input.focus();
                            } else {
                                input.classList.add('d-none');
                                input.value = select.value;
                            }
                        }
                    </script>
                    </div>

                    <!-- Model -->
                    <div class="col-md-6">
                        <label for="model" class="form-label fw-bold text-dark">
                            Model Name / Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('model') is-invalid @enderror" 
                               id="model" 
                               name="model" 
                               placeholder="e.g. ThinkPad T480, Latitude 7490, MacBook Pro 14"
                               value="{{ old('model', $laptop->model) }}" 
                               required>
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Processor (CPU) -->
                    <div class="col-md-6">
                        <label for="processor_cpu" class="form-label fw-bold text-dark">
                            Processor (CPU) <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('processor_cpu') is-invalid @enderror" 
                               id="processor_cpu" 
                               name="processor_cpu" 
                               placeholder="e.g. Intel Core i5-8350U (4 Cores, up to 3.6 GHz)"
                               value="{{ old('processor_cpu', $laptop->processor_cpu) }}" 
                               required>
                        @error('processor_cpu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Memory (RAM) -->
                    <div class="col-md-6">
                        <label for="memory_ram" class="form-label fw-bold text-dark">
                            Memory (RAM) <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('memory_ram') is-invalid @enderror" 
                               id="memory_ram" 
                               name="memory_ram" 
                               placeholder="e.g. 16 GB DDR4-2400 MHz Dual-Channel"
                               value="{{ old('memory_ram', $laptop->memory_ram) }}" 
                               required>
                        @error('memory_ram')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Storage Drive -->
                    <div class="col-md-6">
                        <label for="storage" class="form-label fw-bold text-dark">
                            Storage Drive <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('storage') is-invalid @enderror" 
                               id="storage" 
                               name="storage" 
                               placeholder="e.g. 512 GB NVMe M.2 PCIe SSD"
                               value="{{ old('storage', $laptop->storage) }}" 
                               required>
                        @error('storage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Graphics (GPU) -->
                    <div class="col-md-6">
                        <label for="graphics_gpu" class="form-label fw-bold text-dark">
                            Graphics (GPU) <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('graphics_gpu') is-invalid @enderror" 
                               id="graphics_gpu" 
                               name="graphics_gpu" 
                               placeholder="e.g. Intel UHD Graphics 620 or NVIDIA GTX 1650 4GB"
                               value="{{ old('graphics_gpu', $laptop->graphics_gpu) }}" 
                               required>
                        @error('graphics_gpu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Display -->
                    <div class="col-md-6">
                        <label for="display" class="form-label fw-bold text-dark">
                            Display / Screen <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('display') is-invalid @enderror" 
                               id="display" 
                               name="display" 
                               placeholder="e.g. 14.0&quot; Full HD (1920x1080) IPS Anti-Glare 300 nits"
                               value="{{ old('display', $laptop->display) }}" 
                               required>
                        @error('display')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price Estimate -->
                    <div class="col-md-6">
                        <label for="price_estimate" class="form-label fw-bold text-dark">
                            Price Estimation (Estimasi Harga) <span class="badge bg-secondary-subtle text-secondary small fw-normal ms-1">Optional</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary fw-semibold">Rp</span>
                            <input type="number" 
                                   class="form-control @error('price_estimate') is-invalid @enderror" 
                                   id="price_estimate" 
                                   name="price_estimate" 
                                   placeholder="e.g. 4500000"
                                   value="{{ old('price_estimate', $laptop->price_estimate ? (int)$laptop->price_estimate : '') }}" 
                                   min="100000">
                            @error('price_estimate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text small">Nominal estimasi harga jual (angka saja, contoh: 4500000).</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Hardware Health & Diagnostics (OPTIONAL) -->
        <div class="card shadow-sm border-0 mb-4 border-top border-4 border-success bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-heart-pulse-fill text-success fs-5"></i>
                    <h5 class="fw-bold text-dark mb-0">2. Hardware Health &amp; Diagnostics</h5>
                </div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                    Optional
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <!-- Battery Health -->
                    <div class="col-md-4">
                        <label for="battery_health" class="form-label fw-semibold text-dark">
                            <i class="bi bi-battery-charging text-success me-1"></i> Battery Health
                        </label>
                        <input type="text" 
                               class="form-control @error('battery_health') is-invalid @enderror" 
                               id="battery_health" 
                               name="battery_health" 
                               placeholder="e.g. Good (~86% Health, holds 3-4 hrs)"
                               value="{{ old('battery_health', $laptop->battery_health) }}">
                        @error('battery_health')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text small">Runtime or health percentage.</div>
                    </div>

                    <!-- Storage Health -->
                    <div class="col-md-4">
                        <label for="storage_health" class="form-label fw-semibold text-dark">
                            <i class="bi bi-hdd text-info me-1"></i> Storage Health (SMART)
                        </label>
                        <input type="text" 
                               class="form-control @error('storage_health') is-invalid @enderror" 
                               id="storage_health" 
                               name="storage_health" 
                               placeholder="e.g. 100% Healthy (SMART OK, 0 bad sectors)"
                               value="{{ old('storage_health', $laptop->storage_health) }}">
                        @error('storage_health')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text small">SMART diagnostic status.</div>
                    </div>

                    <!-- Thermal Performance -->
                    <div class="col-md-4">
                        <label for="thermal_performance" class="form-label fw-semibold text-dark">
                            <i class="bi bi-thermometer-half text-warning me-1"></i> Thermal Performance
                        </label>
                        <input type="text" 
                               class="form-control @error('thermal_performance') is-invalid @enderror" 
                               id="thermal_performance" 
                               name="thermal_performance" 
                               placeholder="e.g. Normal (Quiet fans, no throttling)"
                               value="{{ old('thermal_performance', $laptop->thermal_performance) }}">
                        @error('thermal_performance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text small">Cooling &amp; fan stability.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Input / Output & Connectivity (OPTIONAL) -->
        <div class="card shadow-sm border-0 mb-4 border-top border-4 border-secondary bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-plug-fill text-secondary fs-5"></i>
                    <h5 class="fw-bold text-dark mb-0">3. Input / Output (I/O) &amp; Peripherals</h5>
                </div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                    Optional
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-3">
                    <!-- Camera -->
                    <div class="col-sm-6 col-md-3">
                        <label for="camera" class="form-label fw-semibold text-dark small">
                            <i class="bi bi-camera-video text-primary me-1"></i> Camera / Webcam
                        </label>
                        <input type="text" 
                               class="form-control form-control-sm @error('camera') is-invalid @enderror" 
                               id="camera" 
                               name="camera" 
                               placeholder="e.g. Working (720p HD)"
                               value="{{ old('camera', $laptop->camera) }}">
                        @error('camera')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Microphone -->
                    <div class="col-sm-6 col-md-3">
                        <label for="microphone" class="form-label fw-semibold text-dark small">
                            <i class="bi bi-mic text-info me-1"></i> Microphone
                        </label>
                        <input type="text" 
                               class="form-control form-control-sm @error('microphone') is-invalid @enderror" 
                               id="microphone" 
                               name="microphone" 
                               placeholder="e.g. Working (Dual mic clear)"
                               value="{{ old('microphone', $laptop->microphone) }}">
                        @error('microphone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Audio -->
                    <div class="col-sm-6 col-md-3">
                        <label for="audio" class="form-label fw-semibold text-dark small">
                            <i class="bi bi-speaker text-warning me-1"></i> Audio / Speakers
                        </label>
                        <input type="text" 
                               class="form-control form-control-sm @error('audio') is-invalid @enderror" 
                               id="audio" 
                               name="audio" 
                               placeholder="e.g. Working (Stereo clear)"
                               value="{{ old('audio', $laptop->audio) }}">
                        @error('audio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Keyboard -->
                    <div class="col-sm-6 col-md-3">
                        <label for="keyboard" class="form-label fw-semibold text-dark small">
                            <i class="bi bi-keyboard text-success me-1"></i> Keyboard &amp; Trackpad
                        </label>
                        <input type="text" 
                               class="form-control form-control-sm @error('keyboard') is-invalid @enderror" 
                               id="keyboard" 
                               name="keyboard" 
                               placeholder="e.g. Working (Backlight functional)"
                               value="{{ old('keyboard', $laptop->keyboard) }}">
                        @error('keyboard')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Ports & Connectivity -->
                <div>
                    <label for="ports_connectivity" class="form-label fw-semibold text-dark">
                        Ports &amp; Wireless Connectivity Tested
                    </label>
                    <textarea class="form-control @error('ports_connectivity') is-invalid @enderror" 
                              id="ports_connectivity" 
                              name="ports_connectivity" 
                              rows="3" 
                              placeholder="e.g. 2x USB-A 3.1, 1x USB-C Thunderbolt 3, HDMI 1.4, RJ-45 LAN, Wi-Fi 5 (802.11ac), Bluetooth 5.0 (All tested & functional)">{{ old('ports_connectivity', $laptop->ports_connectivity) }}</textarea>
                    @error('ports_connectivity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    <!-- RIGHT COMPANION COLUMN: Quick-Fill, Condition, Software, Addons & Actions (col-lg-5 col-xl-4) -->
    <div class="col-lg-5 col-xl-4">

        <!-- Quick-Fill Preset Card -->
        {{-- <div class="card shadow-sm border border-warning bg-warning-subtle mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-warning text-dark fw-bold px-2 py-1">Time-Saver</span>
                    <span class="badge bg-white text-dark border small">Client-side JS</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">⚡ Quick-Fill Preset: Standard Working Laptop</h5>
                <p class="text-secondary small mb-3">
                    Instantly populates common default values for all optional diagnostics and peripherals with 1 click. You can review and adjust any field.
                </p>
                <div class="d-flex gap-2 mb-2">
                    <button type="button" id="btn-quick-fill" class="btn btn-warning text-dark fw-bold flex-grow-1 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span>Apply Quick-Fill</span>
                    </button>
                    <button type="button" id="btn-clear-optional" class="btn btn-outline-secondary btn-sm px-3" title="Clear all optional fields back to blank">
                        <i class="bi bi-eraser me-1"></i> Clear
                    </button>
                </div>

                <!-- Alert Notice -->
                <div id="quick-fill-alert" class="alert alert-success alert-dismissible fade show mt-2 mb-0 d-none small py-2 px-3" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    <strong>Standard working defaults loaded!</strong> Review or modify before saving.
                    <button type="button" class="btn-close py-2" onclick="document.getElementById('quick-fill-alert').classList.add('d-none')"></button>
                </div>
            </div>
        </div> --}}

        <!-- LAPTOP PHOTOS / GALLERY CARD -->
        <div class="card shadow-sm border-0 mb-4 border-top border-4 border-primary bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-images text-primary fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">Laptop Photos / Gallery</h6>
                </div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                    Optional &bull; Multiple Photos
                </span>
            </div>
            <div class="card-body p-3 p-md-4">
                @if($laptop->hasImage())
                    <div class="mb-3 p-3 bg-light rounded border">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <span class="small text-muted fw-semibold">
                                <i class="bi bi-collection me-1"></i>Current Photos ({{ count($laptop->all_image_urls) }}):
                            </span>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                <label class="form-check-label text-danger small fw-semibold" for="remove_image">
                                    <i class="bi bi-trash me-1"></i> Remove All Photos
                                </label>
                            </div>
                        </div>

                        {{-- If relational images exist, allow selective deletion --}}
                        @if($laptop->images->isNotEmpty())
                            <div class="row g-2">
                                @foreach($laptop->images as $img)
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <div class="card h-100 shadow-sm border p-1 text-center bg-white">
                                            <img src="{{ $img->image_url }}" alt="Laptop Photo" class="img-fluid rounded mb-1" style="height: 110px; width: 100%; object-fit: contain;">
                                            <div class="form-check d-inline-block text-start small mt-auto pt-1 border-top">
                                                <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $img->id }}" id="del_img_{{ $img->id }}">
                                                <label class="form-check-label text-danger small" for="del_img_{{ $img->id }}">
                                                    Delete
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Legacy single image_path --}}
                            <div class="text-center">
                                <img id="current-image-preview" src="{{ $laptop->image_url }}" alt="{{ $laptop->brand }} {{ $laptop->model }}" class="img-fluid rounded shadow-sm" style="max-height: 160px; object-fit: contain;">
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mb-2">
                    <label for="laptop-images-input" class="form-label fw-semibold text-dark small">
                        Upload Photos (JPG, PNG, WEBP — you can select multiple files)
                    </label>
                    <input class="form-control form-control-sm @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror @error('image') is-invalid @enderror" 
                           type="file" 
                           id="laptop-images-input" 
                           name="images[]" 
                           multiple
                           accept="image/*">
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text small">Select one or multiple images. Max 5MB per file. The first photo will be used as the primary catalog thumbnail.</div>
                </div>

                <!-- Live Client-side Multiple Image Preview Box -->
                <div id="new-images-preview-wrapper" class="d-none mt-3 p-3 bg-light rounded border">
                    <div class="small text-success fw-bold mb-2">
                        <i class="bi bi-check-circle me-1"></i>New Photos Selected for Upload:
                    </div>
                    <div id="new-images-preview-grid" class="row g-2"></div>
                </div>
            </div>
        </div>


        <!-- SECTION 4: Physical & Aesthetic Condition (OPTIONAL) -->
        <div class="card shadow-sm border-0 mb-4 border-top border-4 border-info bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-shaded text-info fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">4. Physical &amp; Aesthetic Condition</h6>
                </div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                    Optional
                </span>
            </div>
            <div class="card-body p-3 p-md-4">
                <label for="physical_condition" class="form-label fw-semibold text-dark small">
                    Chassis &amp; Display Condition Assessment
                </label>
                <textarea class="form-control @error('physical_condition') is-invalid @enderror" 
                          id="physical_condition" 
                          name="physical_condition" 
                          rows="4" 
                          placeholder="e.g. Grade A- condition. Minor faint scratches on top outer cover from normal backpack carry. Screen is pristine with no pressure marks or dead pixels. Hinges are firm.">{{ old('physical_condition', $laptop->physical_condition) }}</textarea>
                @error('physical_condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text small">Describe scratches, dents, hinge firmness, or screen condition.</div>
            </div>
        </div>

        <!-- SECTION 5: Software, Security Locks & Inventory Status (OPTIONAL) -->
        <div class="card shadow-sm border-0 mb-4 border-top border-4 border-dark bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-code-square text-dark fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">5. Software, Security &amp; Status</h6>
                </div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                    Optional
                </span>
            </div>
            <div class="card-body p-3 p-md-4">
                <!-- Operating System -->
                <div class="mb-3">
                    <label for="operating_system" class="form-label fw-semibold text-dark small">
                        Operating System
                    </label>
                    <input type="text" 
                           class="form-control @error('operating_system') is-invalid @enderror" 
                           id="operating_system" 
                           name="operating_system" 
                           placeholder="e.g. Windows 11 Pro 64-bit (Activated)"
                           value="{{ old('operating_system', $laptop->operating_system) }}">
                    @error('operating_system')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Locks Cleared -->
                <div class="mb-3">
                    <label for="is_locks_cleared" class="form-label fw-semibold text-dark small">
                        BIOS / Computrace / MDM Locks
                    </label>
                    <select class="form-select @error('is_locks_cleared') is-invalid @enderror" 
                            id="is_locks_cleared" 
                            name="is_locks_cleared">
                        <option value="" {{ old('is_locks_cleared', is_null($laptop->is_locks_cleared) ? '' : ($laptop->is_locks_cleared ? '1' : '0')) === '' ? 'selected' : '' }}>
                            -- Not Specified (Omit from catalog) --
                        </option>
                        <option value="1" {{ old('is_locks_cleared', is_null($laptop->is_locks_cleared) ? '' : ($laptop->is_locks_cleared ? '1' : '0')) === '1' ? 'selected' : '' }}>
                            Yes - Locks Cleared / Unrestricted
                        </option>
                        <option value="0" {{ old('is_locks_cleared', is_null($laptop->is_locks_cleared) ? '' : ($laptop->is_locks_cleared ? '1' : '0')) === '0' ? 'selected' : '' }}>
                            No - Lock Present / Restricted
                        </option>
                    </select>
                    @error('is_locks_cleared')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Availability Switch -->
                <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center gap-3">
                    <input class="form-check-input fs-5 ms-0 mt-0" 
                           type="checkbox" 
                           role="switch" 
                           id="is_available" 
                           name="is_available" 
                           value="1" 
                           {{ old('is_available', $laptop->is_available ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold text-dark small" for="is_available">
                        Mark as "Available for Sale" in public catalog
                    </label>
                </div>
            </div>
        </div>


        <!-- SECTION 6: Addons & Included Accessories (OPTIONAL) -->
        <div class="card shadow-sm border-0 mb-4 border-top border-4 border-warning bg-white">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-box-seam-fill text-warning fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">6. Included Addons &amp; Extras</h6>
                </div>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                    Optional
                </span>
            </div>
            <div class="card-body p-3 p-md-4">
                <label for="addons" class="form-label fw-semibold text-dark small">
                    Included Accessories &amp; Extras
                </label>
                <input type="text" 
                       class="form-control @error('addons') is-invalid @enderror" 
                       id="addons" 
                       name="addons" 
                       placeholder="e.g. Original OEM 65W USB-C Charger &amp; Laptop Sleeve"
                       value="{{ old('addons', $laptop->addons) }}">
                @error('addons')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text small">Charger, cables, bag, or free peripherals.</div>
            </div>
        </div>

        <!-- Sticky Submit / Action Card -->
        <div class="card shadow-sm border-0 bg-light position-sticky" style="top: 80px; z-index: 10;">
            <div class="card-body p-3 p-md-4">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>Save Laptop Specifications</span>
                    </button>
                    <a href="{{ route('laptops.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
    /**
     * Client-side JavaScript Quick-Fill Preset Feature
     * Populates common default values for diagnostics and peripherals.
     * Sellers can freely adjust or override before submitting.
     */
    document.addEventListener('DOMContentLoaded', function () {
        const btnQuickFill = document.getElementById('btn-quick-fill');
        const btnClear = document.getElementById('btn-clear-optional');
        const alertBox = document.getElementById('quick-fill-alert');

        if (btnQuickFill) {
            btnQuickFill.addEventListener('click', function () {
                const presetValues = {
                    battery_health: "Good (Normal wear, holds charge ~85%+)",
                    storage_health: "100% Healthy (SMART OK, 0 bad sectors)",
                    thermal_performance: "Normal (Quiet fan, stable temps under stress)",
                    physical_condition: "Good used condition (Grade A/B). Minor signs of normal daily handling on outer shell. Display screen is clean and free of dead pixels.",
                    ports_connectivity: "All USB ports, HDMI, headphone jack, Wi-Fi, and Bluetooth tested and working properly.",
                    camera: "Working (Tested clear)",
                    microphone: "Working (Clear audio input)",
                    audio: "Working (Stereo speakers tested clear, no distortion)",
                    keyboard: "Working (All keys responsive, backlight functional)",
                    operating_system: "Windows 11 Pro 64-bit (Activated & Clean Installed)",
                    is_locks_cleared: "1",
                    addons: "Original OEM Power Adapter / Charger included"
                };

                // Populate fields and apply visual highlight
                for (const [fieldId, val] of Object.entries(presetValues)) {
                    const el = document.getElementById(fieldId);
                    if (el) {
                        el.value = val;
                        el.classList.add('quick-fill-highlight');
                        setTimeout(() => {
                            el.classList.remove('quick-fill-highlight');
                        }, 1800);
                    }
                }

                // Ensure is_available is checked
                const availEl = document.getElementById('is_available');
                if (availEl) {
                    availEl.checked = true;
                }

                // Show feedback alert banner
                if (alertBox) {
                    alertBox.classList.remove('d-none');
                }
            });
        }

        if (btnClear) {
            btnClear.addEventListener('click', function () {
                if (!confirm('Clear all optional diagnostic and peripheral fields back to blank?')) {
                    return;
                }

                const optionalFields = [
                    'battery_health',
                    'storage_health',
                    'thermal_performance',
                    'physical_condition',
                    'ports_connectivity',
                    'camera',
                    'microphone',
                    'audio',
                    'keyboard',
                    'operating_system',
                    'addons'
                ];

                optionalFields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });

                const locksEl = document.getElementById('is_locks_cleared');
                if (locksEl) locksEl.value = '';

                if (alertBox) {
                    alertBox.classList.add('d-none');
                }
            });
        }

        // Live Multiple Image Files Preview
        const imagesInput = document.getElementById('laptop-images-input');
        const previewWrapper = document.getElementById('new-images-preview-wrapper');
        const previewGrid = document.getElementById('new-images-preview-grid');

        if (imagesInput && previewWrapper && previewGrid) {
            imagesInput.addEventListener('change', function () {
                previewGrid.innerHTML = '';
                const files = this.files;

                if (files && files.length > 0) {
                    previewWrapper.classList.remove('d-none');
                    Array.from(files).forEach((file) => {
                        if (!file.type.startsWith('image/')) return;

                        const col = document.createElement('div');
                        col.className = 'col-6 col-sm-4 col-md-3 text-center';

                        const card = document.createElement('div');
                        card.className = 'card h-100 p-1 border shadow-sm bg-white';

                        const img = document.createElement('img');
                        img.className = 'img-fluid rounded mb-1';
                        img.style.height = '100px';
                        img.style.width = '100%';
                        img.style.objectFit = 'contain';

                        const caption = document.createElement('div');
                        caption.className = 'small text-muted text-truncate px-1';
                        caption.style.fontSize = '0.72rem';
                        caption.textContent = file.name;

                        card.appendChild(img);
                        card.appendChild(caption);
                        col.appendChild(card);
                        previewGrid.appendChild(col);

                        const reader = new FileReader();
                        reader.onload = function (e) {
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                } else {
                    previewWrapper.classList.add('d-none');
                }
            });
        }
    });
</script>
@endpush
