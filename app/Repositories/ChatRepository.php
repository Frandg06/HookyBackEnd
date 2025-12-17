<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\ChatPreviewResource;
use App\Models\Chat;
use App\Models\ChatMessage;

final class ChatRepository
{
    /**
     * Create a new class instance.
     */
    public function findByUuid(string $uid): ?Chat
    {
        return Chat::find($uid);
    }

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

    public function storeMessage(string $chat_uid, string $sender_uid, string $receiver_uid, string $message): ?ChatMessage
    {
        return ChatMessage::create([
            'chat_uid' => $chat_uid,
            'sender_uid' => $sender_uid,
            'receiver_uid' => $receiver_uid,
            'message' => $message,
        ]);
    }

    public function toResourcePreview(Chat $chat)
    {
        return ChatPreviewResource::make($chat);
    }
}
