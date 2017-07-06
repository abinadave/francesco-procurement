<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\RequestForm;
class PrCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $request_form;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RequestForm $request_form)
    {
        $this->request_form = $request_form;
    }
}
