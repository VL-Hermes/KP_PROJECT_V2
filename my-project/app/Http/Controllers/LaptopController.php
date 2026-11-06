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

        $brands = Laptop::select('brand')->distinct()->orderBy('brand')->pluck('brand');

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

        // Handle image upload or image URL
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('laptops', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image_path'] = trim($request->input('image_url'));
        }

        unset($data['image'], $data['image_url'], $data['remove_image']);

        $laptop = Laptop::create($data);

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

        // Handle image update, replacement, or removal
        if ($request->hasFile('image')) {
            // Delete old file if stored locally
            if ($laptop->image_path && !str_starts_with($laptop->image_path, 'http') && Storage::disk('public')->exists($laptop->image_path)) {
                Storage::disk('public')->delete($laptop->image_path);
            }
            $data['image_path'] = $request->file('image')->store('laptops', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($laptop->image_path && !str_starts_with($laptop->image_path, 'http') && Storage::disk('public')->exists($laptop->image_path)) {
                Storage::disk('public')->delete($laptop->image_path);
            }
            $data['image_path'] = null;
        } elseif ($request->filled('image_url')) {
            $data['image_path'] = trim($request->input('image_url'));
        }

        unset($data['image'], $data['image_url'], $data['remove_image']);

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

        // Clean up stored image if it was uploaded locally
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

