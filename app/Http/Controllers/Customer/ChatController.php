<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Services\ChatService;
use App\Http\Controllers\Controller;

final class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function readMessage(string $uid)
    {
        $this->chatService->read($uid);

        return $this->response(['success' => true]);
    }
}
