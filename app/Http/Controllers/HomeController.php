<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\SafariPackage;
use App\Models\Post;

class HomeController extends Controller
{
  public function index()
    {
        // Fetch only featured destinations
        $destinations = Destination::where('is_featured', true)->get();

        // Fetch top-rated safari packages (limit 3 for the homepage)
        $packages = SafariPackage::with('destinations')
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        // Fetch the 3 most recent blog posts
        $posts = Post::latest()->take(3)->get();

        return view('index', compact('destinations', 'packages', 'posts'));
    }
}
