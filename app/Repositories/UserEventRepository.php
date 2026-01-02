<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;
use App\Models\UserEvent;
use Illuminate\Support\Facades\DB;

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
                'superlikes' => $event->superlikes,
            ]
        );
    }

    public function addCredits(string $userUid, string $eventUid, int $likes, int $superLikes): void
    {
        UserEvent::where('user_uid', $userUid)
            ->where('event_uid', $eventUid)
            ->update([
                'likes' => DB::raw("likes + {$likes}"),
                'superlikes' => DB::raw("superlikes + {$superLikes}"),
            ]);
    }
}
