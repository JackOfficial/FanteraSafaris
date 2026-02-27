<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariPackage;
use App\Models\SafariCategory; // Use the specific model
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SafariPackageController extends Controller
{
    public function index()
    {
        // Eager load category and featured photo for performance
        $packages = SafariPackage::with(['category', 'photos' => function($q) {
            $q->where('type', 'featured');
        }])->latest()->paginate(10);
        
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $categories = SafariCategory::all(); // Updated model
        return view('admin.packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'safari_category_id' => 'required|exists:safari_categories,id',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'description' => 'required',
            'location' => 'required|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'itinerary' => 'required|array',
            'itinerary.*.day_number' => 'required|integer',
            'itinerary.*.title' => 'required|string|max:255',
            'itinerary.*.activities' => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $request) {
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

            // 2. Handle Morphable Photo
            if ($request->hasFile('featured_image')) {
                $path = $request->file('featured_image')->store('safaris', 'public');
                $package->photos()->create([
                    'path' => $path,
                    'type' => 'featured',
                ]);
            }

            // 3. Handle Itinerary
            foreach ($validated['itinerary'] as $day) {
                $package->itineraries()->create($day);
            }
        });

        return redirect()->route('admin.packages.index')->with('success', 'Safari package and itinerary created!');
    }

    public function edit(SafariPackage $package)
    {
        $package->load(['itineraries', 'photos']); // Load morph relation
        $categories = SafariCategory::all();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, SafariPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'safari_category_id' => 'required|exists:safari_categories,id',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'description' => 'required',
            'location' => 'required|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'itinerary' => 'required|array',
            'itinerary.*.day_number' => 'required|integer',
            'itinerary.*.title' => 'required|string|max:255',
            'itinerary.*.activities' => 'required|string',
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

            // Handle Photo Update (Delete old featured if new one is uploaded)
            if ($request->hasFile('featured_image')) {
                $oldPhoto = $package->photos()->where('type', 'featured')->first();
                if ($oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto->path);
                    $oldPhoto->delete();
                }

                $path = $request->file('featured_image')->store('safaris', 'public');
                $package->photos()->create([
                    'path' => $path,
                    'type' => 'featured',
                ]);
            }

            // Sync Itinerary
            $currentDayNumbers = collect($validated['itinerary'])->pluck('day_number');
            $package->itineraries()->whereNotIn('day_number', $currentDayNumbers)->delete();

            foreach ($validated['itinerary'] as $dayData) {
                $package->itineraries()->updateOrCreate(
                    ['day_number' => $dayData['day_number']],
                    [
                        'title' => $dayData['title'],
                        'activities' => $dayData['activities'],
                        'meals' => $dayData['meals'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('admin.packages.index')->with('success', 'Safari package updated successfully!');
    }

    public function destroy(SafariPackage $package)
    {
        // Cleanup physical files from polymorphic photos before deleting
        foreach($package->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package and its media deleted.');
    }
}