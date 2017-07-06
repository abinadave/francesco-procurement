<?php

namespace App\Listeners;

use App\Events\PrCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notification as Notification;
use App\User as User;
class sendPrNotification
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
     * @param  PrCreated  $event
     * @return void
     */
    public function handle(PrCreated $event)
    {
        $user = User::findOrFail($event->request_form->requested_by);
        $notif = new Notification;
        $notif->for_position = 'finance-officer';
        $notif->datetime = $event->request_form->datetime;
        $notif->notification_body = 'Engr. ' . $user->name . ' has new Purchase-Request, waiting for your approval';
        $notif->from_user_id = $event->request_form->requested_by;
        $notif->save();
    }
}
