<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Actions\Customer\User\DeleteUserAction;
use Illuminate\Container\Attributes\CurrentUser;

final class DeleteUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, DeleteUserAction $action)
    {
        $action->execute($user);

        return $this->successResponse('i18n.user_deleted');
    }
}
