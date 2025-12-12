<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Event;

use App\Actions\Customer\Events\GetEventsAction;
use App\Http\Controllers\Controller;
use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;

final class GetEventsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EventFilter $filter, EventOrdenator $order, GetEventsAction $action)
    {
        $response = $action->execute($filter, $order);
        return $this->successResponse(__('i18n.events_retrieved_successfully'), $response);
    }
}
