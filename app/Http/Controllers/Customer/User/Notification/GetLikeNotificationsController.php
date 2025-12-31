<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User\Notification;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\User\Notification\GetLikeNotificationsAction;

final class GetLikeNotificationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, Request $request, GetLikeNotificationsAction $action)
    {
        $response = $action->execute($user, $request->integer('page', 1));

        return $this->successResponse(__('i18n.notifications_retrieved'), $response);
    }
}
