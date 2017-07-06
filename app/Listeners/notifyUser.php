<?php

namespace App\Listeners;

use App\Events\SomethingHappenCreateNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification as Notification;
use App\User as User;
class notifyUser
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
     * @param  SomethingHappenCreateNotification  $event
     * @return void
     */
    public function handle(SomethingHappenCreateNotification $event)
    {
        $notif = new Notification;
        $notif->for_position = $event->for_position;
        $notif->datetime = $event->datetime;
        $notif->notification_body = $event->notification_body;
        $notif->from_user_id = $event->requested_by;
        $notif->save();
    }
}
