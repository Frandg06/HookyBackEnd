<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Event;

use App\Models\User;
use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Events\GetEventsAction;
use Illuminate\Container\Attributes\CurrentUser;

final class GetEventsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, EventFilter $filter, EventOrdenator $order, GetEventsAction $action)
    {
        $response = $action->execute($user, $filter, $order, (int) request()->input('page', 1));

        return $this->successResponse(__('i18n.events_retrieved_successfully'), $response);
    }
}
