<?php

namespace App\Listeners;

use App\Events\EventTesting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class proveThatEventIsRunning
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
     * @param  EventTesting  $event
     * @return void
     */
    public function handle(EventTesting $event)
    {
        echo "running...";
    }
}
