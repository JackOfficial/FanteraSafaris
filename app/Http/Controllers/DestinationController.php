<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\SafariPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
public function index(Request $request)
{
    // Fetch destinations for the sidebar dropdown
    $destinations = Destination::all();

    // Fetch packages with filters
    $packages = SafariPackage::where('status', 'published')
        ->with(['destinations', 'categories', 'photo'])
        ->when($request->destination, function($q) use ($request) {
            $q->whereHas('destinations', fn($d) => $d->where('slug', $request->destination));
        })
        ->when($request->price, function($q) use ($request) {
            $q->where('price', '<=', $request->price);
        })
        ->latest()
        ->paginate(9);

    return view('destinations.index', compact('packages', 'destinations'));
}

public function show(Destination $destination)
{
    // 1. Load the packages belonging to this destination
    // 2. Eager load ('with') photos and categories to keep the site fast
    $packages = $destination->packages()
        ->where('status', 'published')
        ->with(['photo', 'categories']) 
        ->latest()
        ->paginate(12);

    // 3. Return the view with the specific destination's data
    return view('destinations.show', [
        'destination' => $destination,
        'packages'    => $packages,
        'title'       => "Best Safaris in {$destination->name} | Fantera Safaris",
        'meta'        => "Explore our hand-picked safari packages in {$destination->name}. " . Str::limit($destination->description, 150)
    ]);
}
}
