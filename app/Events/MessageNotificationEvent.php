<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class MessageNotificationEvent implements ShouldBroadcast
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
                'image_url' => $this->user->userImages->first()?->web_url,
            ],
        ];
    }
}
