<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

final class EventRepository
{
    public function findByUid(string $uid)
    {
        return Event::find($uid);
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function getUpcomingAndActiveEventsForUser(User $user, EventFilter $filter, EventOrdenator $order, int $page = 1)
    {
        return Event::where('end_date', '>', Carbon::now()->toDateTimeString())
            ->whereDoesntHave('users2', function ($query) use ($user) {
                $query->where('uid', $user->uid);
            })
            ->filter($filter)
            ->sort($order)
            ->paginate(1, ['*'], 'page', $page);
    }
}
