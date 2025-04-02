<?php

namespace App\Http\Controllers;

use App\Http\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function retrieve()
    {
        $response = $this->chatService->retrieve($this->user());

        return $this->response($response);
    }

    public function show(string $uid)
    {
        $response = $this->chatService->show($uid);
        return $this->response($response);
    }

    public function sendMessage(Request $request, string $uid)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $response = $this->chatService->sendMessage($uid, $request->message);

        return $this->response($response);
    }


    public function readMessage(string $uid)
    {
        $this->chatService->read($uid);
        return $this->response(['success' => true]);
    }
}
