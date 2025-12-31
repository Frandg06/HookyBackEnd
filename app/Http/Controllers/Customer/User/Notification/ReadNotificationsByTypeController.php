<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User\Notification;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use App\Actions\Customer\User\Notification\ReadNotificationsByTypeAction;
use App\Http\Requests\Customer\User\Notification\ReadNotificationsByTypeRequest;

final class ReadNotificationsByTypeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, ReadNotificationsByTypeRequest $request, ReadNotificationsByTypeAction $action)
    {
        $type = $request->string('type')->toString();

        $response = $action->execute($user, $type);

        return $this->successResponse('Notifications marked as read', $response);
    }
}
