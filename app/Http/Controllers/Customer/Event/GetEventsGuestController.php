<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Event;

use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Http\Controllers\Controller;
use App\Actions\Customer\Event\GetEventsGuestAction;

final class GetEventsGuestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EventFilter $filter, EventOrdenator $order, GetEventsGuestAction $action)
    {
        $response = $action->execute($filter, $order, (int) request()->input('page', 1));

        return $this->successResponse(__('i18n.events_retrieved_successfully'), $response);
    }
}
