<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Event;

use App\Actions\Customer\Auth\EventAttachAction;
use App\Actions\Customer\Events\GetActiveEventByCompanyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Event\EventAttachByCompanyRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Request;

final class EventAttachByCompanyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, EventAttachByCompanyRequest $request, GetActiveEventByCompanyAction $eventAction, EventAttachAction $eventAttachAction)
    {
        $event = $eventAction->execute($request->input('company_uid'));

        $eventAttachAction->execute($user->uid, $event->uid);

        return $this->successResponse('i18n.event_attached_by_company');
    }
}
