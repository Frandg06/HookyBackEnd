<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatPreviewResource;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function retrieve(Request $request)
    {
        $chats = Chat::where('event_uid', $this->user()->event->uid)
            ->where(function ($query) {
                $query->where('user1_uid', $this->user()->uid)
                    ->orWhere('user2_uid', $this->user()->uid);
            })
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

        $chat->messages()->create([
            'chat_uid' => $uid,
            'sender_uid' => $this->user()->uid,
            'message' => $request->message,
        ]);

        // Logic to send message goes here

        return $this->response(['success' => true]);
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
