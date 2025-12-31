<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Chat;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Chat\GetChatsAction;
use Illuminate\Container\Attributes\CurrentUser;

final class GetChatsController extends Controller
{
    public function __invoke(#[CurrentUser] User $user, GetChatsAction $action, Request $request)
    {
        $page = $request->integer('page', 1);

        $chats = $action->execute($user, $page);

        return $this->successResponse('Chats retrieved successfully.', $chats);
    }
}
