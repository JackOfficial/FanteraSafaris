<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SafariPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SafariController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function guideItinerary()
    {
        // For Fantera Safaris: Fetch only bookings assigned to this guide
        // Assumes you have a 'guide_id' column in your bookings table
        $itineraries = Booking::where('guide_id', Auth::id())
            ->where('status', 'confirmed')
            ->orderBy('start_date', 'asc')
            ->get();

        return view('guide.itinerary', compact('itineraries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
   public function show(string $slug)
    {
        // Find the package by slug, or fail with a 404 error
        $package = SafariPackage::with(['destinations', 'categories', 'photo'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('packages.show', compact('package'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
