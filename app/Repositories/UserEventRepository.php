<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;
use App\Models\User;
use App\Models\UserEvent;

final class UserEventRepository
{
    public function findEventByUuid(string $event_uuid): ?Event
    {
        return Event::where('uid', $event_uuid)->first();
    }

    public function attachUserToEvent(User $user, Event $event): UserEvent
    {
        return UserEvent::updateOrCreate(
            [
                'user_uid' => $user->uid,
                'event_uid' => $event->uid,
            ],
            [
                'logged_at' => now(),
                'likes' => $event->likes,
                'super_likes' => $event->super_likes,
            ]
        );
    }
}
