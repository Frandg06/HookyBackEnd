<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

final class MessageNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user, public ChatMessage $message) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('App.Models.User.'.$this->user->uid);
    }

    public function broadcastAs(): string
    {
        return 'message-notification';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'user' => [
                'uid' => $this->user->uid,
                'name' => $this->user->name,
                'image_url' => $this->user->images->first()?->web_url,
            ],
        ];
    }
}
