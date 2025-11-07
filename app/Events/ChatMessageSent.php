<?php

namespace App\Events;

use App\Models\InternalChat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(InternalChat $chat)
    {
        $this->chat = $chat->load('customer', 'admin');
    }

    public function broadcastOn()
    {
        return new Channel('internal-chat');
    }

    public function broadcastWith()
{
    return [
        'chat' => [
            'id' => $this->chat->id,
            'message' => $this->chat->message,
            'image_paths' => $this->chat->image_paths ?? [],
            'customer_id' => $this->chat->customer_id,
            'admin_id' => $this->chat->admin_id,
            'created_at' => $this->chat->created_at->toDateTimeString(),
            'read_at' => $this->chat->read_at ? $this->chat->read_at->toDateTimeString() : null,

            'customer' => $this->chat->customer ? [
                'id' => $this->chat->customer->id,
                'name' => $this->chat->customer->name,
                'phone' => $this->chat->customer->phone,
            ] : null,

            'admin' => $this->chat->admin ? [
                'id' => $this->chat->admin->id,
                'name' => $this->chat->admin->name,
            ] : null,
        ]
    ];
}
}