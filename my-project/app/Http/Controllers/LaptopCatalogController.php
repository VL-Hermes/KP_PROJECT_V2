<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\Request;

class LaptopCatalogController extends Controller
{
    /**
     * Display a listing of laptops in the public catalog.
     */
    public function index(Request $request)
    {
        $query = Laptop::query();

        // Search term across brand, model, CPU, RAM, storage, GPU
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%")
                  ->orWhere('processor_cpu', 'LIKE', "%{$search}%")
                  ->orWhere('graphics_gpu', 'LIKE', "%{$search}%")
                  ->orWhere('memory_ram', 'LIKE', "%{$search}%")
                  ->orWhere('storage', 'LIKE', "%{$search}%");
            });
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        // Availability filter: default to showing available, but allow viewing all or sold
        $availability = $request->input('availability', 'available');
        if ($availability === 'available') {
            $query->where('is_available', true);
        } elseif ($availability === 'unavailable') {
            $query->where('is_available', false);
        }
        // If 'all', do not filter by is_available

        // Sort order
        $sortBy = $request->input('sort', 'latest');
        if ($sortBy === 'brand_asc') {
            $query->orderBy('brand', 'asc')->orderBy('model', 'asc');
        } elseif ($sortBy === 'brand_desc') {
            $query->orderBy('brand', 'desc')->orderBy('model', 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $laptops = $query->paginate(9)->withQueryString();

        // Get distinct brands for filter dropdown
        $brands = Laptop::select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $totalAvailable = Laptop::where('is_available', true)->count();
        $totalAll = Laptop::count();

        return view('catalog.index', compact('laptops', 'brands', 'totalAvailable', 'totalAll'));
    }

    /**
     * Display the specified laptop specifications in detail.
     */
    public function show(Laptop $laptop)
    {
        return view('catalog.show', compact('laptop'));
    }
}

