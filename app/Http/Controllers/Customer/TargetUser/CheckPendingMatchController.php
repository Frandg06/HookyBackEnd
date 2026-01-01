<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\TargetUser;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\TargetUser\CheckPendingMatchAction;

final class CheckPendingMatchController extends Controller
{
    /**
     * Handle the incoming request to check if user can confirm a match.
     */
    public function __invoke(
        #[CurrentUser] User $user,
        string $uid,
        CheckPendingMatchAction $action
    ) {
        $response = $action->execute($user, $uid);

        return $this->successResponse('User to confirm retrieved', $response);
    }
}
