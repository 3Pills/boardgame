<?php

namespace App\Listeners;

class UserEventListener
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

    /**
     * Handle the event.
     *
     * @param  none  $event
     * @return void
     */
    public function subscribe($events) {
        $events->listen(
            'App\Events\User\Registered',
            'App\Listeners\UserEventListener@userRegistered'
        );
    }
}
