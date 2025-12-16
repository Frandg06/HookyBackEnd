<?php

declare(strict_types=1);

namespace App\Events;

use App\Http\Resources\MessageResource;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class PrivateChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public ChatMessage $message) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('App.Models.Chat.'.$this->message->chat_uid);
    }

    public function broadcastAs(): string
    {
        return 'private-chat-message';
    }

    public function broadcastWith(): array
    {

        return MessageResource::make($this->message)->resolve();
    }
}
