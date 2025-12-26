<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User\Notification;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\User\Notification\GetHookNotificationsAction;

final class GetHookNotificationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, GetHookNotificationsAction $action)
    {
        $response = $action->execute($user);

        return $this->successResponse(__('i18n.notifications_retrieved'), $response);
    }
}
