<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;
    public $sender;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('chat-channel');
    }

    /**
     * The event name to broadcast as.
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    /**
     * Data to broadcast.
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'sender' => $this->sender,
        ];
    }
}
