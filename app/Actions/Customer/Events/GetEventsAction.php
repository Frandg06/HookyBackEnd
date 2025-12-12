<?php

declare(strict_types=1);

namespace App\Actions\Customer\Events;

use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

final readonly class GetEventsAction
{
    /**
     * Execute the action.
     */
    public function execute(EventFilter $filter, EventOrdenator $order)
    {
        return DB::transaction(function () use ($filter, $order) {
            $events = Event::where('st_date', '>=', now()->toDateString())
                ->filter($filter)
                ->sort($order)
                ->get();

            return [
                'events' => $events->map(function (Event $event) {
                    return [
                        'uid' => $event->uid,
                        'name' => $event->name,
                        'st_date' => $event->st_date,
                        'end_date' => $event->end_date,
                        'room_name' => $event->room_name,
                        'city' => $event->city,
                        'banner_image' => $event->banner_image,
                    ];
                }),
                'cities' => $events->pluck('city')->unique()->values(),
            ];
        });
    }
}
