<?php

declare(strict_types=1);

namespace App\Actions\Customer\Events;

use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final readonly class GetEventsAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, EventFilter $filter, EventOrdenator $order)
    {
        return DB::transaction(function () use ($user, $filter, $order) {
            $events = Event::where('end_date', '>', Carbon::now()->toDateTimeString())
                ->whereDoesntHave('users2', function ($query) use ($user) {
                    $query->where('uid', $user->uid);
                })
                ->filter($filter)
                ->sort($order)
                ->get();

            return $events->map(function (Event $event) use ($user) {
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
            });
        });
    }
}
