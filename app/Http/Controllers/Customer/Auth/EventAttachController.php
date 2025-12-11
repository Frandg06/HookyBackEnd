<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\EventAttachAction;
use App\Actions\Customer\Auth\MeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\EventAttachRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventAttachController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EventAttachRequest $request, EventAttachAction $eventAttachAction, MeAction $userAction): JsonResponse
    {
        $user = user();
        $event_uuid = $request->input('event_uid');
        $eventAttachAction->execute($user, $event_uuid);
        $userResource = $userAction->execute($user);
        return $this->successResponse('Event attached successfully', $userResource);
    }
}
