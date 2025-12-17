<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class LikeNotificationEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;


    /**
     * Create a new event instance.
    */
    public function __construct(public User $user, public User $likedUser) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' . $this->likedUser->uid);

    }

    public function broadcastAs(): string
    {
        return 'like-notification';
    }

    public function broadcastWith(): array
    {
        if (!$this->likedUser->is_premium) {
            return [];
        }
        return [
            'image_url' => $this->likedUser->userImages->first()->web_url ,
            'name' => $this->likedUser->name,
        ];

    }
}
