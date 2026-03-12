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
    // 1. Fetch all destinations 
    // We use withCount to show "12 Tours" on the destination card in the UI
    $destinations = Destination::withCount(['packages' => function($query) {
            $query->where('status', 'published');
        }])
        ->when($request->country, function($q) use ($request) {
            $q->where('country', $request->country);
        })
        ->latest()
        ->paginate(12);

    // 2. Optional: Get a list of unique countries for a filter dropdown
    $countries = Destination::select('country')->distinct()->pluck('country');

    return view('destinations.index', compact('destinations', 'countries'));
}

public function show(Destination $destination)
{
    $packages = $destination->packages()
        ->where('status', 'published')
        ->with('photo') // Eager load for speed
        ->paginate(6);

    return view('destinations.show', compact('destination', 'packages'));
    }
}
