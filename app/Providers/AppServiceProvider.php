<?php

namespace App\Providers;

use App\Listeners\UpdateLastLogin;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        Schema::defaultStringLength(191);
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

       Gate::define('view-assigned-safari', function (User $user, Booking $booking) {
    if ($user->hasRole('safari-manager')) return true;

    // Check if the user is the Guide OR the Client who booked it
    return $user->id === $booking->guide_id || $user->id === $booking->user_id;
});

view()->composer('admin.layouts.sidebar', function ($view) {
        $unreadCount = \App\Models\ContactMessage::where('is_read', false)->count();
        $view->with('unreadCount', $unreadCount);
    });

    Event::listen(
            Login::class,
            UpdateLastLogin::class
        );

        Paginator::useBootstrapFour();

    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
