<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Chat;

use App\Actions\Customer\Chat\SendMessageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Chat\SendMessageRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;

final class SendMessageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, SendMessageRequest $request, SendMessageAction $action)
    {
        $reposnse = $action->execute(
            $user,
            $request->input('chat_uid'),
            $request->input('message'),
        );

        return $this->successResponse('message_notify', $reposnse);
    }
}
