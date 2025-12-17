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

final class SuperlikeNotificationEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user, public User $targetUser) {}

    /**
    * Get the channels the event should broadcast on.
    *
    * @return array<int, Channel>
    */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' . $this->targetUser->uid);

    }

    public function broadcastAs(): string
    {
        return 'superlike-notification';
    }

    public function broadcastWith(): array
    {
        return [
            'image_url' => $this->targetUser->userImages->first()->web_url ,
            'name' => $this->targetUser->name,
        ];
    }
}
