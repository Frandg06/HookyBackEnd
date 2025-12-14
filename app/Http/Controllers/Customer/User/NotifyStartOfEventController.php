<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User;

use App\Actions\Customer\User\NotifyStartOfEventAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\User\NotifyStartOfEventRequest;
use App\Jobs\ScheduedlEmails;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class NotifyStartOfEventController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, NotifyStartOfEventRequest $request, NotifyStartOfEventAction $action): JsonResponse
    {
        $action->execute($user, $request->input('event_uid'));

        return $this->successResponse('notification_scheduled');
    }
}
