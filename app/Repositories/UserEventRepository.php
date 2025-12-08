<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;
use App\Models\User;
use App\Models\UserEvent;

final class UserEventRepository
{
    public function attachUserToEvent(User $user, Event $event): UserEvent
    {
        return UserEvent::createOrUpdate(
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
