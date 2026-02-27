<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariPackage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SafariPackageController extends Controller
{
    /**
     * Display a listing of the safari packages.
     */
    public function index()
    {
        $categories = Category::all();
        $packages = SafariPackage::latest()->paginate(10);
        return view('admin.packages.index', compact('packages', 'categories'));
    }

    /**
     * Show the form for creating a new safari package.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package and its itinerary.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'description' => 'required',
            'location' => 'required|string',
            'itinerary' => 'required|array',
            'itinerary.*.day_number' => 'required|integer',
            'itinerary.*.title' => 'required|string|max:255',
            'itinerary.*.activities' => 'required|string',
        ]);

        DB::transaction(function () use ($validated) {
            $package = SafariPackage::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'price' => $validated['price'],
                'duration_days' => $validated['duration_days'],
                'description' => $validated['description'],
                'location' => $validated['location'],
            ]);

            foreach ($validated['itinerary'] as $day) {
                $package->itineraries()->create($day);
            }
        });

        return redirect()->route('admin.packages.index')->with('success', 'Safari package and itinerary created!');
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(SafariPackage $package)
    {
        // Load the itineraries so the Livewire component can receive them
        $package->load('itineraries');
        $categories = Category::all();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    /**
     * Update the specified package and sync the itinerary.
     */
    public function update(Request $request, SafariPackage $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'description' => 'required',
            'location' => 'required|string',
            'itinerary' => 'required|array',
            'itinerary.*.day_number' => 'required|integer',
            'itinerary.*.title' => 'required|string|max:255',
            'itinerary.*.activities' => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $package) {
            $package->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'price' => $validated['price'],
                'duration_days' => $validated['duration_days'],
                'description' => $validated['description'],
                'location' => $validated['location'],
            ]);

            // Sync Itinerary: Delete removed days and update/create remaining ones
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

    /**
     * Remove the specified package.
     */
    public function destroy(SafariPackage $package)
    {
        // cascade deletion handles itineraries if set in migration
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted.');
    }
}