<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
class SomethingHappenCreateNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $for_position, $datetime, $notification_body, $from_user_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($for_position, $datetime, $notification_body, $from_user_id)
    {
        $this->for_position = $for_position;
        $this->datetime = $datetime;
        $this->notification_body = $notification_body;
        $this->from_user_id = $from_user_id;
    }

}
