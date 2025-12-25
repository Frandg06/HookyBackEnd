<?php

declare(strict_types=1);

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;

final class EventRepository
{
    public function findByUid(string $uid, array $relations = []): ?Event
    {
        return Event::query()->when(count($relations) > 0, fn ($query) => $query->with($relations))->find($uid);
    }

    public function findBySlug(string $slug, array $relations = []): ?Event
    {
        return Event::query()->when(count($relations) > 0, fn ($query) => $query->with($relations))->where('slug', $slug)->first();
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function getUpcomingAndActiveEventsForUser(User $user, EventFilter $filter, EventOrdenator $order, int $page = 1)
    {
        return Event::withCount('users2')->where('end_date', '>', Carbon::now()->toDateTimeString())
            ->whereDoesntHave('users2', function ($query) use ($user) {
                $query->where('uid', $user->uid);
            })
            ->filter($filter)
            ->sort($order)
            ->paginate(10, ['*'], 'page', $page);
    }
}
