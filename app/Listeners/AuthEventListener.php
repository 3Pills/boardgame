<?php

namespace App\Listeners;

class AuthEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function logAuthenticationAttempt($event) {

    }

    public function logSuccessfulLogin($event) {

    }

    public function logSuccessfulLogout($event) {

    }

    public function logLockout($event) {

    }

    /**
     * Handle the event.
     *
     * @param  none  $event
     * @return void
     */
    public function subscribe($events) {
        $events->listen(
            'Illuminate\Auth\Events\Attempting',
            'App\Listeners\PlayerEventListener@logAuthenticationAttempt'
        );
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\AuthEventListener@logSuccessfulLogin'
        );
        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\AuthEventListener@logSuccessfulLogout'
        );
        $events->listen(
            'Illuminate\Auth\Events\Lockout',
            'App\Listeners\AuthEventListener@logLockout'
        );
    }
}
