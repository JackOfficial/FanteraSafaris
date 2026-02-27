<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\SafariController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SafariPackageController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest & Visitors)
|--------------------------------------------------------------------------
*/

Route::get('/', function () { return view('index'); });
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// Safari & Tour Catalog
Route::get('/safaris', [SafariController::class, 'index'])->name('safaris.index');
Route::view('/tour', 'tours.index')->name('tours.index');
Route::view('/tour-details', 'tours.show')->name('tours.show');

// Hotels & Packages
Route::view('/hotel', 'hotel')->name('hotel.index');
Route::view('/hotel-single', 'hotel-single')->name('hotel.show');
Route::view('/packages', 'packages.index')->name('packages.index');
Route::view('/package', 'packages.show')->name('packages.show');

// Blog Content
Route::view('/blog', 'blog')->name('blog.index');
Route::view('/blog-single', 'blog-single')->name('blog.show');

// Public Contact Form Routes
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Authentication & Socialite
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::get('/redirect/{provider}', [SocialLoginController::class, 'redirect'])->name('auth.redirect');
    Route::get('/callback/{provider}', [SocialLoginController::class, 'callback'])->name('auth.callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated Client Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Future Client-specific routes (e.g., My Bookings)
});

/*
|--------------------------------------------------------------------------
| Staff & Admin Routes (Role Protected)
|--------------------------------------------------------------------------
*/

// Admin & Safari Managers: Inventory, Fleet, & Operations
Route::middleware(['auth', 'role:super-admin|safari-manager'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        Route::get('/dashboard', fn() => view('admin.index'))->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('posts', PostController::class);
        Route::resource('categories', CategoryController::class);
        // Inquiry/Message Routes
        Route::get('/messages', [MessageController::class, 'index'])->name('messages.inbox');
        Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
        Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::resource('packages', SafariPackageController::class);
        
        // Fleet Management
        Route::controller(FleetController::class)->group(function () {
            Route::get('/fleet', 'index')->name('fleet.index');
            Route::post('/fleet/{vehicle}/assign', 'assign')->name('fleet.assign');
        });

});

// Reservation Agents: Bookings & Customer Service
Route::middleware(['auth', 'role:reservation-agent|super-admin'])
    ->prefix('reservations')
    ->name('reservations.')
    ->group(function () {
        Route::resource('bookings', BookingController::class);
});

// Tour Guide Routes: Itineraries & Assigned Trips
Route::middleware(['auth', 'role:tour-guide|super-admin']) // Super Admin added for troubleshooting
    ->prefix('guide')
    ->name('guide.')
    ->group(function () {
        
        // The main itinerary dashboard for guides
        Route::get('/itinerary', [SafariController::class, 'guideItinerary'])
            ->name('itinerary');
            
        // Example: Route to start a specific trip (useful for field operations)
        Route::patch('/itinerary/{booking}/start', [SafariController::class, 'startTrip'])
            ->name('itinerary.start');
});