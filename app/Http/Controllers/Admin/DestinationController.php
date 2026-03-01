<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::latest()->paginate(10);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($request->name);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination added to the map!');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => "required|string|max:255|unique:destinations,name,{$destination->id}",
            'country' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['slug'] = Str::slug($request->name);
        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            if ($destination->image) Storage::disk('public')->delete($destination->image);
            $validated['image'] = $request->file('image')->store('destinations', 'public');
        }

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated.');
    }

    public function destroy(Destination $destination)
    {
        if ($destination->image) Storage::disk('public')->delete($destination->image);
        $destination->delete();
        return back()->with('success', 'Destination removed.');
    }

    public function bulkDelete(Request $request)
{
    // Validate that we actually got IDs
    $request->validate([
        'ids' => 'required|string',
    ]);

    // Convert "1,2,3" string from Alpine into [1, 2, 3] array
    $ids = explode(',', $request->ids);

    // Fetch them all to handle image deletion from storage
    $destinations = Destination::whereIn('id', $ids)->get();

    foreach ($destinations as $destination) {
        if ($destination->image) {
            Storage::disk('public')->delete($destination->image);
        }
        $destination->delete();
    }

    return back()->with('success', count($ids) . ' destinations deleted successfully.');
}
}