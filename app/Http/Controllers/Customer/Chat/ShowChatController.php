<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Chat\ShowChatAction;
use App\Http\Requests\Customer\Chat\ShowChatRequest;

final class ShowChatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ShowChatRequest $request, ShowChatAction $action)
    {
        $chat_uid = $request->string('chat_uid')->toString();
        $page = $request->integer('page', 1);

        $chat = $action->execute($chat_uid, $page);

        return $this->successResponse('Chat retrieved successfully', $chat);
    }
}
