<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Hook;

final class HookRepository
{
    public function store(string $user1Uid, string $user2Uid, string $eventUid): Hook
    {

        return Hook::create([
            'user1_uid' => $user1Uid,
            'user2_uid' => $user2Uid,
            'event_uid' => $eventUid,
        ]);
    }
}
