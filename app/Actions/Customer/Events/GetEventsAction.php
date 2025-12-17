<?php

declare(strict_types=1);

namespace App\Actions\Customer\Events;

use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Models\Event;
use App\Models\User;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\DB;

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
                'events' => $events->map(function (Event $event) use ($user) {
                    return [
                        'uid' => $event->uid,
                        'name' => $event->name,
                        'st_date' => $event->st_date,
                        'end_date' => $event->end_date,
                        'room_name' => $event->room_name,
                        'city' => $event->city,
                        'banner_image' => $event->banner_image,
                        'is_active' => $event->is_active,
                        'is_sheduled' => ! $event->is_active && ! $event->is_finished,
                        'is_notified' => $event->scheduledNotifications->where('user_uid', $user->uid)->first() ? true : false,
                    ];
                }),
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'next_page' => $events->currentPage() + 1 > $events->lastPage() ? null : $events->currentPage() + 1,
                    'total_pages' => $events->lastPage(),
                ],
            ];

        });
    }
}
