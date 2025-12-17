<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Auth\MeAction;
use Illuminate\Container\Attributes\CurrentUser;

final class MeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, MeAction $action)
    {
        return $this->successResponse('i18n.authenticated_user', $action->execute($user));
    }
}
