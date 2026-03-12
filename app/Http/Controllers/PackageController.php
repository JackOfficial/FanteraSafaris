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
        // 1. Start the query with eager loading for better performance
        $query = SafariPackage::query()
            ->with(['destinations', 'categories', 'photos'])
            ->withAvg('reviews', 'rating') // Pulls average rating automatically
            ->where('status', 'published');

        // 2. Apply Search Filter
        $query->when($request->search, function ($q, $search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('destinations', function ($sq) use ($search) {
                  $sq->where('name', 'like', "%{$search}%");
              });
        });

        // 3. Apply Region/Destination Filter
        $query->when($request->region, function ($q, $region) {
            $q->whereHas('destinations', function ($sq) use ($region) {
                $sq->where('slug', $region); // Assumes you have a slug column
            });
        });

        // 4. Apply Price Range Filter
        $query->when($request->price_range, function ($q, $price) {
            $q->where('price', '<=', $price);
        });

        // 5. Sort by most recent or featured
        $packages = $query->latest()
                          ->paginate(12)
                          ->withQueryString(); // Keeps filters active during pagination

        return view('packages.index', compact('packages'));
    }

    /**
     * Display the specific safari details.
     */
    public function show(string $slug): View
    {
        $package = SafariPackage::where('slug', $slug)
            ->with(['destinations', 'categories', 'photos', 'itineraries'])
            ->firstOrFail();

        // Increment views for the admin "Hot" badge logic
        $package->increment('views');

        return view('packages.show', compact('package'));
    }
}