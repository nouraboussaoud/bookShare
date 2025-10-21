<?php

namespace App\Events;

use App\Models\EventChatMessage;
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

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(EventChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('event-chat.' . $this->message->group_event_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
                'avatar' => strtoupper(substr($this->message->user->name, 0, 2))
            ],
            'created_at' => $this->message->created_at->diffForHumans(),
            'timestamp' => $this->message->created_at->toIso8601String(),
            'moderation_status' => $this->message->moderation_status,
            'ai_used' => $this->message->ai_used,
            'reply_to' => $this->message->reply_to_message_id ? [
                'user' => $this->message->reply_to_user,
                'message' => $this->message->reply_to_content
            ] : null
        ];
    }
}
