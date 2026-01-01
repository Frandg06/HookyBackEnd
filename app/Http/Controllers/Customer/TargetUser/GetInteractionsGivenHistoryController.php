<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\TargetUser;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\TargetUser\GetInteractionsGivenHistoryAction;

final class GetInteractionsGivenHistoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, GetInteractionsGivenHistoryAction $action)
    {
        if (! $user->is_premium) {
            return $this->errorResponse('This feature is only available for premium users', '/home', 403);
        }

        $users = $action->execute($user);

        return $this->successResponse('Interactions history retrieved successfully', $users);
    }
}
