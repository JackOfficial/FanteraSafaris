<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\SafariController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/blog', function () {
    return view('blog');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/hotel', function () {
    return view('hotel');
});

Route::get('/tour', function () {
    return view('tours.index');
});

Route::get('/tour-details', function () {
    return view('tours.show');
});

Route::get('/packages', function () {
    return view('packages.index');
});

Route::get('/package', function () {
    return view('packages.show');
});

Route::get('/hotel-single', function () {
    return view('hotel-single');
});

Route::get('/blog-single', function () {
    return view('blog-single');
});

//Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () { 
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // Route::post('/comment', [PageController::class, 'post']);
    // Route::post('/deleteComment/{id}', [PageController::class, 'deleteComment']);
});

Route::get('/safaris', [SafariController::class, 'index'])->name('safaris.index');

// Social login routes
Route::get('/auth/redirect/{provider}', [SocialLoginController::class, 'redirect']);
Route::get('/auth/callback/{provider}', [SocialLoginController::class, 'callback']);

// Routes for Safari Managers & Admins only
Route::middleware(['auth', 'role:safari-manager|super-admin'])->group(function () {
    Route::get('/fleet', [FleetController::class, 'index'])->name('fleet.index');
    Route::post('/fleet/{vehicle}/assign', [FleetController::class, 'assign'])->name('fleet.assign');
});

// Routes for Reservation Agents
Route::middleware(['auth', 'role:reservation-agent'])->group(function () {
    Route::resource('bookings', BookingController::class);
});