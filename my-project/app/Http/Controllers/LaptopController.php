<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaptopController extends Controller
{
    /**
     * Display the inventory management dashboard.
     */
    public function index(Request $request)
    {
        $query = Laptop::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%")
                  ->orWhere('processor_cpu', 'LIKE', "%{$search}%")
                  ->orWhere('storage', 'LIKE', "%{$search}%");
            });
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->input('status') === 'available') {
                $query->where('is_available', true);
            } elseif ($request->input('status') === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        $laptops = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Statistics for dashboard summary cards
        $totalCount = Laptop::count();
        $availableCount = Laptop::where('is_available', true)->count();
        $unavailableCount = Laptop::where('is_available', false)->count();

        $brands = Laptop::select('brand')
            ->selectRaw('count(*) as count')
            ->groupBy('brand')
            ->orderBy('brand')
            ->get();

        return view('inventory.index', compact('laptops', 'totalCount', 'availableCount', 'unavailableCount', 'brands'));
    }

    /**
     * Show the form for creating a new laptop inventory item.
     */
    public function create()
    {
        $laptop = new Laptop();
        // Default to available for newly added inventory
        $laptop->is_available = true;

        return view('inventory.create', compact('laptop'));
    }

    /**
     * Store a newly created laptop in the database.
     */
    public function store(Request $request)
    {
        $validated = $this->validateLaptop($request);
        $data = $this->prepareData($validated, $request);

        // Store first image into image_path if provided via single upload or image_url
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('laptops', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image_path'] = trim($request->input('image_url'));
        }

        unset($data['image'], $data['images'], $data['image_url'], $data['remove_image'], $data['delete_images']);

        $laptop = Laptop::create($data);

        // If single image was uploaded, register in laptop_images
        if (!empty($data['image_path'])) {
            $laptop->images()->create([
                'image_path' => $data['image_path'],
                'sort_order' => 0,
            ]);
        }

        // Handle multiple images upload (images[])
        if ($request->hasFile('images')) {
            $order = $laptop->images()->count();
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('laptops', 'public');
                    $laptop->images()->create([
                        'image_path' => $path,
                        'sort_order' => $order++,
                    ]);

                    // If primary image_path was empty, set it to the first uploaded image
                    if (empty($laptop->image_path)) {
                        $laptop->image_path = $path;
                        $laptop->save();
                    }
                }
            }
        }

        return redirect()->route('laptops.index')
            ->with('success', "Laptop \"{$laptop->brand} {$laptop->model}\" has been successfully added to inventory!");
    }

    /**
     * Display the specified laptop (redirects to public catalog detail or shows in inventory).
     */
    public function show(Laptop $laptop)
    {
        return redirect()->route('catalog.show', $laptop);
    }

    /**
     * Show the form for editing an existing laptop.
     */
    public function edit(Laptop $laptop)
    {
        return view('inventory.edit', compact('laptop'));
    }

    /**
     * Update the specified laptop in the database.
     */
    public function update(Request $request, Laptop $laptop)
    {
        $validated = $this->validateLaptop($request);
        $data = $this->prepareData($validated, $request);

        // 1. Handle complete image removal if requested
        if ($request->boolean('remove_image')) {
            foreach ($laptop->images as $img) {
                if ($img->image_path && !str_starts_with($img->image_path, 'http') && Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
            if ($laptop->image_path && !str_starts_with($laptop->image_path, 'http') && Storage::disk('public')->exists($laptop->image_path)) {
                Storage::disk('public')->delete($laptop->image_path);
            }
            $data['image_path'] = null;
        }

        // 2. Handle deletion of specific selected images (delete_images[])
        if ($request->filled('delete_images')) {
            $deleteIds = (array) $request->input('delete_images');
            $imagesToDelete = $laptop->images()->whereIn('id', $deleteIds)->get();
            foreach ($imagesToDelete as $delImg) {
                if ($delImg->image_path && !str_starts_with($delImg->image_path, 'http') && Storage::disk('public')->exists($delImg->image_path)) {
                    Storage::disk('public')->delete($delImg->image_path);
                }
                if ($laptop->image_path === $delImg->image_path) {
                    $laptop->image_path = null;
                }
                $delImg->delete();
            }
        }

        // 3. Handle single replacement image upload (image)
        if ($request->hasFile('image')) {
            if ($laptop->image_path && !str_starts_with($laptop->image_path, 'http') && Storage::disk('public')->exists($laptop->image_path)) {
                Storage::disk('public')->delete($laptop->image_path);
            }
            $newPath = $request->file('image')->store('laptops', 'public');
            $data['image_path'] = $newPath;
            $laptop->images()->create([
                'image_path' => $newPath,
                'sort_order' => $laptop->images()->count(),
            ]);
        } elseif ($request->filled('image_url')) {
            $data['image_path'] = trim($request->input('image_url'));
        }

        // 4. Handle multiple additional images upload (images[])
        if ($request->hasFile('images')) {
            $order = $laptop->images()->count();
            foreach ($request->file('images') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('laptops', 'public');
                    $laptop->images()->create([
                        'image_path' => $path,
                        'sort_order' => $order++,
                    ]);

                    if (empty($data['image_path']) && empty($laptop->image_path)) {
                        $data['image_path'] = $path;
                    }
                }
            }
        }

        // If primary image_path is null but images remain, point to the first remaining image
        if (empty($data['image_path']) && empty($laptop->image_path)) {
            $firstImg = $laptop->images()->first();
            if ($firstImg) {
                $data['image_path'] = $firstImg->image_path;
            }
        }

        unset($data['image'], $data['images'], $data['image_url'], $data['remove_image'], $data['delete_images']);

        $laptop->update($data);

        return redirect()->route('laptops.index')
            ->with('success', "Laptop \"{$laptop->brand} {$laptop->model}\" specifications have been updated successfully!");
    }

    /**
     * Remove the specified laptop from the inventory.
     */
    public function destroy(Laptop $laptop)
    {
        $name = "{$laptop->brand} {$laptop->model}";

        // Clean up all stored images locally
        foreach ($laptop->images as $img) {
            if ($img->image_path && !str_starts_with($img->image_path, 'http') && Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }

        if ($laptop->image_path && !str_starts_with($laptop->image_path, 'http') && Storage::disk('public')->exists($laptop->image_path)) {
            Storage::disk('public')->delete($laptop->image_path);
        }

        $laptop->delete();

        return redirect()->route('laptops.index')
            ->with('success', "Laptop \"{$name}\" has been removed from inventory.");
    }

    /**
     * Quick toggle availability status.
     */
    public function toggleAvailability(Laptop $laptop)
    {
        $laptop->is_available = !$laptop->is_available;
        $laptop->save();

        $status = $laptop->is_available ? 'Available' : 'Unavailable / Sold';
        return redirect()->back()
            ->with('success', "Status for \"{$laptop->brand} {$laptop->model}\" changed to: {$status}.");
    }

    /**
     * Validate incoming laptop request data.
     */
    protected function validateLaptop(Request $request): array
    {
        return $request->validate([
            // Core Hardware - REQUIRED
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'processor_cpu' => ['required', 'string', 'max:150'],
            'memory_ram' => ['required', 'string', 'max:100'],
            'storage' => ['required', 'string', 'max:150'],
            'graphics_gpu' => ['required', 'string', 'max:150'],
            'display' => ['required', 'string', 'max:150'],

            // Commercial & Media - NULLABLE (Optional)
            'price_estimate' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],

            // Diagnostics & Health - NULLABLE (Optional)
            'battery_health' => ['nullable', 'string', 'max:255'],
            'storage_health' => ['nullable', 'string', 'max:255'],
            'thermal_performance' => ['nullable', 'string', 'max:255'],

            // Physical Condition - NULLABLE (Optional)
            'physical_condition' => ['nullable', 'string', 'max:3000'],

            // I/O & Connectivity - NULLABLE (Optional)
            'ports_connectivity' => ['nullable', 'string', 'max:3000'],
            'camera' => ['nullable', 'string', 'max:255'],
            'microphone' => ['nullable', 'string', 'max:255'],
            'audio' => ['nullable', 'string', 'max:255'],
            'keyboard' => ['nullable', 'string', 'max:255'],

            // Software & Licensing - NULLABLE (Optional)
            'operating_system' => ['nullable', 'string', 'max:255'],
            'is_locks_cleared' => ['nullable'],
            'is_available' => ['nullable'],

            // Addons - NULLABLE (Optional)
            'addons' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Prepare validated data, ensuring nullable fields are cleanly null and booleans normalized.
     */
    protected function prepareData(array $validated, Request $request): array
    {
        // Convert any empty or whitespace strings into actual NULLs
        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $trimmed = trim($value);
                $validated[$key] = $trimmed === '' ? null : $trimmed;
            }
        }

        // Availability flag
        $validated['is_available'] = $request->boolean('is_available');

        // Locks cleared tri-state: '1' -> true, '0' -> false, '' or null -> null
        if ($request->has('is_locks_cleared') && $request->input('is_locks_cleared') !== '' && !is_null($request->input('is_locks_cleared'))) {
            $validated['is_locks_cleared'] = filter_var($request->input('is_locks_cleared'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        } else {
            $validated['is_locks_cleared'] = null;
        }

        return $validated;
    }
}

