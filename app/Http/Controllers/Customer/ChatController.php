<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Services\ChatService;
use App\Http\Controllers\Controller;

final class ChatController extends Controller
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

    public function show(string $uid, Request $request)
    {
        $page = $request->integer('page', 1);

        $response = $this->chatService->show($uid, $page);

        return $this->response($response);
    }

    public function readMessage(string $uid)
    {
        $this->chatService->read($uid);

        return $this->response(['success' => true]);
    }
}
