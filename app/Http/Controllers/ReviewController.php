<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'package_id' => 'required|exists:safari_packages,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10',
    ]);

    Review::create([
        'package_id' => $request->package_id,
        'user_id' => auth()->id(), // Automatically takes the logged-in user's ID
        'rating' => $request->rating,
        'comment' => $request->comment,
        'is_published' => false, // Keep it false for admin review
    ]);

    return back()->with('success', 'Thank you! Your review is awaiting moderation.');
}
}
