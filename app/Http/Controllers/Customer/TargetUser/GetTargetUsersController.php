<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\TargetUser;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\TargetUser\GetTargetUsersAction;

final class GetTargetUsersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, GetTargetUsersAction $action)
    {
        $users = $action->execute($user);

        return $this->successResponse('Users retrieved successfully', $users);
    }
}
