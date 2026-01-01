<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Chat;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\Chat\MarkChatAsReadAction;
use App\Http\Requests\Customer\Chat\MarkChatAsReadRequest;

final class MarkChatAsReadController extends Controller
{
    /**
     * Handle the incoming request to mark chat messages as read.
     */
    public function __invoke(
        #[CurrentUser] User $user,
        MarkChatAsReadRequest $request,
        MarkChatAsReadAction $action
    ) {
        $chat_uid = $request->string('uid')->toString();
        $action->execute($user, $chat_uid);

        return $this->successResponse('Messages marked as read');
    }
}
