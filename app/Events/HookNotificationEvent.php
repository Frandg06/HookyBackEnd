<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class HookNotificationEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly User $user1,
        public readonly User $user2,
        public readonly string $event_uid,
        public readonly string $chat_uid
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->user1->uid),
            new PrivateChannel('App.Models.User.'.$this->user2->uid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'hook-notification';
    }

    public function broadcastWith(): array
    {
        return [
            'event_uid' => $this->event_uid,
            'user1' => [
                'uid' => $this->user1->uid,
                'name' => $this->user1->name,
                'image_url' => $this->user1->images->first()?->web_url,
            ],
            'user2' => [
                'uid' => $this->user2->uid,
                'name' => $this->user2->name,
                'image_url' => $this->user2->images->first()?->web_url,
            ],
            'chat_uid' => $this->chat_uid,
        ];

    }
}
