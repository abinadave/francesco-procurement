<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\EventTesting' => [
            'App\Listeners\proveThatEventIsRunning',
        ],
        'App\Events\PrCreated' => [
            'App\Listeners\sendPrNotification',
        ],
        'App\Events\SomethingHappenCreateNotification' => [
            'App\Listeners\notifyUser',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
