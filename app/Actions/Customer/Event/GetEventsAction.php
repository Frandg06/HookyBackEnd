<?php

declare(strict_types=1);

namespace App\Actions\Customer\Event;

use App\Models\User;
use App\Http\Filters\EventFilter;
use Illuminate\Support\Facades\DB;
use App\Http\Orders\EventOrdenator;
use App\Repositories\EventRepository;
use App\Http\Resources\Customer\Event\EventCollection;

final readonly class GetEventsAction
{
    public function __construct(private readonly EventRepository $eventRepository) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, EventFilter $filter, EventOrdenator $order, int $page): EventCollection
    {
        return DB::transaction(function () use ($user, $filter, $order, $page) {
            $events = $this->eventRepository->getUpcomingAndActiveEventsForUser($user, $filter, $order, $page);

            return EventCollection::make($events);
        });
    }
}
