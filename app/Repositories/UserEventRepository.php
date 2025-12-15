<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;
use App\Models\UserEvent;

final class UserEventRepository
{
    public function findEventByUuid(string $event_uuid): ?Event
    {
        return Event::where('uid', $event_uuid)->first();
    }

    public function attachUserToEvent(string $user_uuid, Event $event): UserEvent
    {
        return UserEvent::updateOrCreate(
            [
                'user_uid' => $user_uuid,
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
