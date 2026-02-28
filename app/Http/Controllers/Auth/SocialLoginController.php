<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
{
    try {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        // 1. Find or create the user
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'password' => bcrypt(str()->random(24)), // Slightly longer for extra security
                'avatar' => $socialUser->getAvatar(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]
        );

        // 2. Assign Safari Role to new users
        if ($user->wasRecentlyCreated) {
            // Check if this is the first user ever - make them Super Admin
            if (User::count() === 1) {
                $user->assignRole('super-admin');
            } else {
                // Default all other Google sign-ups to 'client' or 'user'
                $user->assignRole('client'); 
            }
        }

        // 3. Mark email as verified (Google users are pre-verified)
        if (is_null($user->email_verified_at)) {
            $user->markEmailAsVerified();
        }

        Auth::login($user, true);

        // 4. Safari-Specific Redirect Logic
        return $this->handleSafariRedirect($user);

    } catch (\Exception $e) {
        // Log the error for debugging safari booking issues
        logger()->error('Social Login Error: ' . $e->getMessage());
        return redirect('/login')->with('error', 'Authentication failed. Please try again.');
    }
}

/**
 * Handle redirects based on Safari Business Roles
 */
protected function handleSafariRedirect($user)
{
    if ($user->hasRole('super-admin') || $user->hasRole('safari-manager')) {
        return redirect()->route('admin.dashboard'); // Manage fleet & bookings
    }

    if ($user->hasRole('tour-guide')) {
        return redirect()->route('guide.itinerary'); // View their specific tours
    }

    // Default: Regular clients/visitors
    return redirect()->route('home');
}
}
