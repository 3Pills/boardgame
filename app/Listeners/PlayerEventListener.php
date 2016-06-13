<?php

namespace App\Listeners;

class PlayerEventListener
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

    public function playerJoin($event) {

    }

    public function playerQuit($event) {

    }

    public function playerRoll($event) {

    }

    public function changeState($event) {

    }

    /**
     * Handle the event.
     *
     * @param  none  $event
     * @return void
     */
    public function subscribe($events) {
        $events->listen(
            'App\Events\Game\PlayerJoin',
            'App\Listeners\PlayerEventListener@playerJoin'
        );
        $events->listen(
            'App\Events\Game\PlayerQuit',
            'App\Listeners\PlayerEventListener@playerQuit'
        );
        $events->listen(
            'App\Events\Game\PlayerRoll',
            'App\Listeners\PlayerEventListener@playerRoll'
        );
        $events->listen(
            'App\Events\Game\ChangeState',
            'App\Listeners\PlayerEventListener@changeState'
        );
    }
}
