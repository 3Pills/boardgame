<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\User\Registered' => [
            'App\Listeners\UserEventListener@userRegistered',
            //'App\Listeners\UserEventListener@registerEmailConfirmation',
        ],
        'App\Events\Game\PlayerJoin' => [
            'App\Listeners\PlayerEventListener@playerJoin',
        ],
        'App\Events\Game\PlayerQuit' => [
            'App\Listeners\PlayerEventListener@playerQuit',
        ],
        'App\Events\Game\PlayerRoll' => [
            'App\Listeners\PlayerEventListener@playerRoll',
        ],
        'App\Events\Game\ChangeState' => [
            'App\Listeners\PlayerEventListener@changeState',
        ],

        'Illuminate\Auth\Events\Attempting' => [
            'App\Listeners\AuthEventListener@logAuthenticationAttempt',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\AuthEventListener@logSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\AuthEventListener@logSuccessfulLogout',
        ],
        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\AuthEventListener@logLockout',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\UserEventListener',
        'App\Listeners\PlayerEventListener',
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
