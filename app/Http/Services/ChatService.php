<?php

declare(strict_types=1);

namespace App\Http\Services;

use Throwable;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Customer\Chat\ChatPreviewResource;

final class ChatService extends Service
{
    public function read(string $uid)
    {
        DB::beginTransaction();
        try {
            ChatMessage::where('chat_uid', $uid)
                ->whereNot('sender_uid', $this->user()->uid)
                ->where('read_at', false)
                ->update(['read_at' => true]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function store($user1, $user2, $event)
    {
        $chat = Chat::create([
            'user1_uid' => $user1,
            'user2_uid' => $user2,
            'event_uid' => $event,
            'created_at' => now(),
        ]);

        return ChatPreviewResource::make($chat);
    }
}
