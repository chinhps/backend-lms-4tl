<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $message;
    public $slug;
    public $name;
    public $message_type;

    public function __construct($slug, $user_id, $name, $message, $message_type)
    {
        $this->slug = $slug;
        $this->user_id = $user_id;
        $this->message = $message;
        $this->name = $name;
        $this->message_type = $message_type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastAs()
    {
        return 'private-room';
    }
    public function broadcastOn()
    {
        return new PresenceChannel('room.' . $this->slug);
    }
}
