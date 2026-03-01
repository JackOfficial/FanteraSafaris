<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariPackage;
use App\Models\SafariCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SafariPackageController extends Controller
{
    public function index()
    {
        // Eager loading 'category' and 'photo' (morphOne) for better performance
        $packages = SafariPackage::with(['category', 'photo'])
            ->latest()
            ->paginate(10);
        
        return view('admin.packages.index', compact('packages'));
    }

    public function exportPdf()
    {
        // Fetch all packages with their destination relationship
        $packages = SafariPackage::with('destination', 'category')->get();

        // Load the view and pass the data
        $pdf = Pdf::loadView('admin.packages.pdf', compact('packages'));

        // Download the file
        return $pdf->download('safari-packages-list.pdf');
    }

    public function create()
    {
        $categories = SafariCategory::orderBy('name')->get();
        return view('admin.packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name',
            'safari_category_id' => 'required|exists:safari_categories,id',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'description' => 'required|string',
            'location' => 'required|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'itinerary' => 'required|array',
            'itinerary.*.day_number' => 'required|integer',
            'itinerary.*.title' => 'required|string|max:255',
            'itinerary.*.activities' => 'required|string',
            'itinerary.*.meals' => 'nullable|string',
            'itinerary.*.accommodation' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            // 1. Create the Package
            $package = SafariPackage::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'safari_category_id' => $validated['safari_category_id'],
                'price' => $validated['price'],
                'duration_days' => $validated['duration_days'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'status' => $validated['status'],
            ]);

            // 2. Handle Polymorphic Featured Photo
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('safaris/featured', 'public');
                $package->photo()->create([
                    'path' => $path,
                    'type' => 'featured',
                ]);
            }

            // 3. Handle Itinerary creation
            foreach ($validated['itinerary'] as $day) {
                $package->itineraries()->create($day);
            }

            return redirect()->route('admin.packages.index')
                ->with('success', 'Safari package and itinerary created successfully!');
        });
    }

    public function edit(SafariPackage $package)
    {
        // Load relationships: itineraries (sorted) and all polymorphic photos
        $package->load(['itineraries' => fn($q) => $q->orderBy('day_number'), 'photos', 'category']);
        $categories = SafariCategory::orderBy('name')->get();
        
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, SafariPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name,' . $package->id,
            'safari_category_id' => 'required|exists:safari_categories,id',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'description' => 'required',
            'location' => 'required|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'itinerary' => 'required|array',
            'itinerary.*.day_number' => 'required|integer',
            'itinerary.*.title' => 'required|string|max:255',
            'itinerary.*.activities' => 'required|string',
            'itinerary.*.meals' => 'nullable|string',
            'itinerary.*.accommodation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $request, $package) {
            $package->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'safari_category_id' => $validated['safari_category_id'],
                'price' => $validated['price'],
                'duration_days' => $validated['duration_days'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'status' => $validated['status'],
            ]);

            // Handle Photo Update
            if ($request->hasFile('featured_image')) {
                // Delete existing featured photo if it exists
                $oldPhoto = $package->photo()->where('type', 'featured')->first();
                if ($oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto->path);
                    $oldPhoto->delete();
                }

                $path = $request->file('featured_image')->store('safaris/featured', 'public');
                $package->photo()->create([
                    'path' => $path,
                    'type' => 'featured',
                ]);
            }

            // Sync Itinerary: Clear existing and re-create to ensure order and clean data
            $package->itineraries()->delete();
            foreach ($validated['itinerary'] as $dayData) {
                $package->itineraries()->create($dayData);
            }
        });

        return redirect()->route('admin.packages.index')
            ->with('success', 'Safari package updated successfully!');
    }

    public function destroy(SafariPackage $package)
    {
        return DB::transaction(function () use ($package) {
            // Cleanup all polymorphic photos (featured + gallery)
            foreach($package->photos as $photo) {
                Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }

            // Soft delete the package (since your migration has softDeletes)
            $package->delete();

            return redirect()->route('admin.packages.index')
                ->with('success', 'Package and associated media deleted.');
        });
    }
}