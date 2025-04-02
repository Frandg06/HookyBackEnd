<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatPreviewResource;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{

    public function retrieve(Request $request)
    {
        $chats = Chat::where('event_uid', $this->user()->event->uid)
            ->where(function ($query) {
                $query->where('user1_uid', $this->user()->uid)
                    ->orWhere('user2_uid', $this->user()->uid);
            })
            ->orderBy(function ($query) {
                $query->select('created_at')
                    ->from('chat_messages')
                    ->whereColumn('chat_messages.chat_uid', 'chats.uid')
                    ->orderBy('created_at', 'desc')
                    ->limit(1);
            }, 'desc')
            ->get();

        $response = ChatPreviewResource::collection($chats);

        return $this->response($response);
    }

    public function show(string $uid)
    {
        $chat = Chat::where('event_uid', $this->user()->event->uid)
            ->where(function ($query) {
                $query->where('user1_uid', $this->user()->uid)
                    ->orWhere('user2_uid', $this->user()->uid);
            })
            ->where('uid', $uid)
            ->firstOrFail();

        $resource = ChatResource::make($chat);

        return $this->response($resource);
    }

    public function sendMessage(Request $request, string $uid)
    {

        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chat = Chat::findOrFail($uid);

        $userToSend = $this->user()->uid === $chat->user1_uid ? $chat->user2_uid : $chat->user1_uid;

        $message = $chat->messages()->create([
            'chat_uid' => $uid,
            'sender_uid' => $this->user()->uid,
            'message' => $request->message,
        ]);

        $response = MessageResource::make($message);

        $url = config('services.ws_api.send_message');

        Http::withHeaders([
            'Authorization' => 'Bearer ' . request()->bearerToken(),
            'Accept' => 'application/json'
        ])->post($url, [
            'message' => $response,
            'reciverUid' => $userToSend,
        ]);

        return $this->response($response);
    }


    public function readMessage(string $uid)
    {
        $messages = ChatMessage::where('chat_uid', $uid)
            ->where('sender_uid', '!=', $this->user()->uid)
            ->where('read_at', false)
            ->update(['read_at' => true]);

        return $this->response(['success' => true]);
    }
}
