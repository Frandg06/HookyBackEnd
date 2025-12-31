<?php

declare(strict_types=1);

namespace App\Http\Services;

use Throwable;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ChatPreviewResource;
use App\Http\Resources\Customer\Chat\ChatCollection;

final class ChatService extends Service
{
    public function retrieve($user)
    {
        $chats = Chat::with('messages')
            ->where('event_uid', $user->event->uid)
            ->whereAny(['user1_uid', 'user2_uid'], $user->uid)
            ->whereHas('messages')
            ->withMax('messages', 'created_at')
            ->orderByDesc('messages_max_created_at')
            ->paginate(100, ['*'], 'page', 1);

        return ChatPreviewResource::collection($chats);
    }

    public function show(string $uid, int $page): ChatCollection
    {
        $chat = Chat::find($uid);

        $messages = $chat->messages()
            ->orderByDesc('created_at')
            ->orderByDesc('uid')
            ->paginate(100, ['*'], 'page', $page);

        return ChatCollection::make($messages)->withChat($chat);
    }

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
