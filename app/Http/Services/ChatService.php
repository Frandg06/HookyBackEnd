<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Events\PrivateChatMessageEvent;
use App\Http\Resources\ChatPreviewResource;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ChatService extends Service
{
    public function retrieve($user)
    {
        $chats = Chat::where('event_uid', $user->event->uid)
            ->whereAny(['user1_uid', 'user2_uid'], $user->uid)
            ->orderBy(function ($query) {
                $query->select('created_at')
                    ->from('chat_messages')
                    ->whereColumn('chat_messages.chat_uid', 'chats.uid')
                    ->orderBy('created_at', 'desc')
                    ->limit(1);
            }, 'desc')
            ->get();

        return ChatPreviewResource::collection($chats);
    }

    public function show(string $uid): ChatResource
    {
        $chat = Chat::findOrFail($uid);

        return ChatResource::make($chat);
    }

    public function sendMessage(string $uid, string $message)
    {
        DB::beginTransaction();
        try {
            $chat = Chat::findOrFail($uid);

            $userToSend = $this->user()->uid === $chat->user1_uid ? $chat->user2_uid : $chat->user1_uid;

            $message = $chat->messages()->create([
                'chat_uid' => $uid,
                'sender_uid' => $this->user()->uid,
                'receiver_uid' => $userToSend,
                'message' => $message,
            ]);

            PrivateChatMessageEvent::dispatch($message);

            DB::commit();

            return MessageResource::make($message);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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
