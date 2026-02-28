<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class UpdateLastLogin
{

    /**
     * The current request instance.
     */
    protected $request;

    /**
     * Create the event listener.
     */
   public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
       // $event->user gives us the User instance that just logged in
        $event->user->update([
            'last_login_at' => now(),
            'last_login_ip' => $this->request->ip(),
        ]);
    }
}
