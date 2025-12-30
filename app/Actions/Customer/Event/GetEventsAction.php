<?php

declare(strict_types=1);

namespace App\Actions\Customer\Event;

use App\Models\User;
use App\Http\Filters\EventFilter;
use Illuminate\Support\Facades\DB;
use App\Http\Orders\EventOrdenator;
use App\Repositories\EventRepository;
use App\Http\Resources\Customer\Event\EventResource;

final readonly class GetEventsAction
{
    public function __construct(private readonly EventRepository $eventRepository) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, EventFilter $filter, EventOrdenator $order, int $page)
    {
        return DB::transaction(function () use ($user, $filter, $order, $page) {
            $events = $this->eventRepository->getUpcomingAndActiveEventsForUser($user, $filter, $order, $page);

            return [
                'events' => EventResource::collection($events),
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'next_page' => $events->currentPage() + 1 > $events->lastPage() ? null : $events->currentPage() + 1,
                    'total_pages' => $events->lastPage(),
                    'prev_page' => $events->currentPage() - 1 < 1 ? null : $events->currentPage() - 1,
                ],
            ];

        });
    }
}
