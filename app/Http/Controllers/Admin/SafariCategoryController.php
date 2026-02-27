<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SafariCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SafariCategoryController extends Controller
{
    public function index()
    {
        $categories = SafariCategory::latest()->paginate(10);
        return view('admin.safari_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.safari_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:safari_categories,name',
            'description' => 'nullable|string',
        ]);

        SafariCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.safari-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(SafariCategory $safariCategory)
    {
        return view('admin.safari_categories.edit', compact('safariCategory'));
    }

    public function update(Request $request, SafariCategory $safariCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:safari_categories,name,' . $safariCategory->id,
            'description' => 'nullable|string',
        ]);

        $safariCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.safari-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(SafariCategory $safariCategory)
    {
        // Check if category has packages before deleting to prevent orphan records
        if ($safariCategory->safariPackages()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has safari packages assigned.');
        }

        $safariCategory->delete();
        return redirect()->route('admin.safari-categories.index')->with('success', 'Category deleted.');
    }
}