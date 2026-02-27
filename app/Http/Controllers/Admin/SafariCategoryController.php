<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SafariCategoryController extends Controller
{
    public function index()
    {
        // withCount prevents N+1 queries when showing package numbers
        // with('photo') eager loads the polymorphic relation
        $categories = SafariCategory::with('photo')
            ->withCount('safariPackages')
            ->latest()
            ->paginate(10);

        return view('admin.safari_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.safari_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:safari_categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $category = SafariCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
        ]);

        // Handle Polymorphic Image Upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $category->photo()->create([
                'path' => $path,
                'type' => 'cover' // Distinguishes it from other photo types if needed
            ]);
        }

        return redirect()->route('admin.safari-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(SafariCategory $safariCategory)
    {
        // Load the photo relationship to ensure it's available in the view
        $safariCategory->load('photo');
        return view('admin.safari_categories.edit', compact('safariCategory'));
    }

    public function update(Request $request, SafariCategory $safariCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:safari_categories,name,' . $safariCategory->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $safariCategory->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
        ]);

        // Update Polymorphic Image
        if ($request->hasFile('image')) {
            // Delete old physical file and DB record if they exist
            if ($safariCategory->photo) {
                Storage::disk('public')->delete($safariCategory->photo->path);
                $safariCategory->photo()->delete();
            }

            // Store new file
            $path = $request->file('image')->store('categories', 'public');
            $safariCategory->photo()->create([
                'path' => $path,
                'type' => 'cover'
            ]);
        }

        return redirect()->route('admin.safari-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(SafariCategory $safariCategory)
    {
        // Prevent deletion if packages exist
        if ($safariCategory->safariPackages()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has safari packages assigned.');
        }

        // Cleanup: Delete associated polymorphic photo from disk and DB
        if ($safariCategory->photo) {
            Storage::disk('public')->delete($safariCategory->photo->path);
            $safariCategory->photo()->delete();
        }

        $safariCategory->delete();

        return redirect()->route('admin.safari-categories.index')
            ->with('success', 'Category and its assets deleted.');
    }
}