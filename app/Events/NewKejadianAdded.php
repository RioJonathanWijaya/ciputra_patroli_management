<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// app/Events/NewKejadianAdded.php
class NewKejadianAdded implements ShouldBroadcast
{
    public $kejadianData;

    public function __construct($kejadianData)
    {
        $this->kejadianData = $kejadianData;
    }

    public function broadcastOn()
    {
        return new Channel('kejadian-updates');
    }
}