<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\TargetUser;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\TargetUser\GetInteractionsRecivedHistoryAction;

final class GetInteractionsRecivedHistoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, Request $request, GetInteractionsRecivedHistoryAction $action)
    {
        if (! $user->is_premium) {
            return $this->errorResponse('This feature is only available for premium users', '/home', 403);
        }

        $users = $action->execute($user, $request->integer('page', 1));

        return $this->successResponse('Interactions history retrieved successfully', $users);
    }
}
