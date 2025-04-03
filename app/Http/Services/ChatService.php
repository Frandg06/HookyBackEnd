<?php

namespace App\Http\Services;

use App\Http\Resources\ChatPreviewResource;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatNotify;
use App\Models\Notifify;
use Illuminate\Support\Facades\DB;
use Throwable;

class ChatService extends Service
{
    public function retrieve($user)
    {
        $chats = Chat::where('event_uid', $user->event->uid)
          ->where(function ($query) use ($user) {
              $query->where('user1_uid', $user->uid)
                ->orWhere('user2_uid', $user->uid);
          })
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
              'message' => $message,
            ]);

            $response = MessageResource::make($message);

            $notify = new ChatNotify([
              'reciber_uid' => $userToSend,
              'type_id' => Notifify::MESSAGE,
              'sender_uid' => $this->user()->uid,
              'sender_name' => $this->user()->name,
              'payload' => $response,
            ]);

            $notify->emit();
            DB::commit();
            return $response;
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError($e, __CLASS__, __FUNCTION__);
            $this->responseError('Error sending message', 500);
        }
    }

    public function read(string $uid)
    {
        DB::beginTransaction();
        try {
            ChatMessage::where('chat_uid', $uid)
              ->where('sender_uid', '!=', $this->user()->uid)
              ->where('read_at', false)
              ->update(['read_at' => true]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            $this->logError($e, __CLASS__, __FUNCTION__);
            $this->responseError('Error marking message as read', 500);
        }
    }



    public function store($user1, $user2, $event)
    {
        try {
            $chat = Chat::create([
              'user1_uid' => $user1,
              'user2_uid' => $user2,
              'event_uid' => $event,
              'created_at' => now()
            ]);
            return ChatPreviewResource::make($chat);
        } catch (Throwable $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            $this->responseError('Error storing chat data', 500);
        }
    }
}
