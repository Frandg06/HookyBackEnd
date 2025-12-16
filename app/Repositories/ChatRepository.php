<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\ChatPreviewResource;
use App\Models\Chat;

final class ChatRepository
{
    /**
     * Create a new class instance.
     */
    public function store(string $user1, string $user2, string $event)
    {
        $chat = Chat::create([
            'user1_uid' => $user1,
            'user2_uid' => $user2,
            'event_uid' => $event,
            'created_at' => now(),
        ]);

        return $chat;
    }

    public function toResourcePreview(Chat $chat)
    {
        return ChatPreviewResource::make($chat);
    }
}
