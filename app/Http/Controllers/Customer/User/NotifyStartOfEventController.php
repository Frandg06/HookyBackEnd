<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Actions\Customer\User\NotifyStartOfEventAction;
use App\Http\Requests\Customer\User\NotifyStartOfEventRequest;

final class NotifyStartOfEventController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, NotifyStartOfEventRequest $request, NotifyStartOfEventAction $action): JsonResponse
    {
        $action->execute($user, $request->input('event_uid'));

        return $this->successResponse('i18n.notification_scheduled');
    }
}
