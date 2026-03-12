<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\SafariPackage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    /**
     * Display a listing of the safari packages with filters.
     */
  public function index(Request $request): View
{
    // 1. Eager load everything needed for the UI
    $query = SafariPackage::query()
        ->with(['destinations', 'categories', 'photos'])
        // We only want the average of reviews that have been approved by Admin
        ->withAvg(['reviews' => function ($q) {
            $q->where('is_published', true);
        }], 'rating')
        ->where('status', 'published');

    // 2. Apply Search Filter
    $query->when($request->search, function ($q, $search) {
        // Grouping the search to avoid issues with the 'where status = published' clause
        $q->where(function ($sub) use ($search) {
            $sub->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('destinations', function ($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%");
                });
        });
    });

    // 3. Apply Region Filter (Matching the select name in your sidebar)
    $query->when($request->region, function ($q, $region) {
        $q->whereHas('destinations', function ($sq) use ($region) {
            $sq->where('slug', $region);
        });
    });

    // 4. Apply Price Filter
    $query->when($request->price_range, function ($q, $price) {
        $q->where('price', '<=', $price);
    });

    // 5. Finalize and Paginate
    $packages = $query->latest()
                      ->paginate(12)
                      ->withQueryString();

    return view('packages.index', compact('packages'));
}

    /**
     * Display the specific safari details.
     */
 public function show(SafariPackage $package): View
{
    // 1. Eager load everything, including ONLY published reviews
    $package->load([
        'destinations', 
        'categories', 
        'photos', 
        'itineraries',
        'reviews' => function($query) {
            $query->where('is_published', true)->latest();
        }
    ]);

    // 2. Load the average rating for approved reviews only
    $package->loadAvg(['reviews' => function($query) {
        $query->where('is_published', true);
    }], 'rating');

    // 3. Increment views (Standard for "Popular" logic)
    $package->increment('views');

    return view('packages.show', compact('package'));
}
}