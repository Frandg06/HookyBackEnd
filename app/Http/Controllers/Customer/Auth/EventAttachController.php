<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Auth\MeAction;
use App\Actions\Customer\Auth\EventAttachAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Http\Requests\Customer\Auth\EventAttachRequest;

final class EventAttachController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser] User $user, EventAttachRequest $request, EventAttachAction $eventAttachAction, MeAction $userAction): JsonResponse
    {
        $event_uuid = $request->input('event_uid');
        $eventAttachAction->execute($user->uid, $event_uuid);
        $userResource = $userAction->execute($user);

        return $this->successResponse('Event attached successfully', $userResource);
    }
}
