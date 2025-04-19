<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Http\Resources\EventResource;
use App\Http\Resources\Exports\EventExportResource;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventService extends Service
{
    public function store(array $data): EventResource
    {
        try {
            $data['st_date'] = $data['st_date'].' '.$data['st_hour'];
            $data['end_date'] = $data['end_date'].' '.$data['end_hour'];
            $data['code'] = Str::uuid();

            $this->validateEvent($data['st_date'], $data['end_date'], null);

            $event = $this->company()->events()->create($data);

            DB::commit();

            return EventResource::make($event);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getCalendarEvents(array $dates): array
    {

        $events = [];
        foreach ($dates as $date) {
            $start = Carbon::parse($date)->startOfMonth();
            $end = Carbon::parse($date)->endOfMonth();

            $query = $this->company()->events()
                ->whereDate('st_date', '>=', $start)
                ->whereDate('st_date', '<=', $end)
                ->get(['name', 'st_date as start', 'end_date as end', 'colors', 'uid'])->toArray();

            $events = array_merge($events, $query);
        }

        return $events;
    }

    public function getEvents(EventFilter $filtrer, EventOrdenator $ordenator, $limit = 10): array
    {
        $events = $this->company()->events()
            ->with(['users', 'tickets'])
            ->filter($filtrer)
            ->sort($ordenator)
            ->paginate($limit);

        return [
            'data' => EventResource::collection($events),
            'current_page' => $events->currentPage(),
            'last_page' => $events->lastPage(),
            'total' => $events->total(),
            'per_page' => $events->perPage(),
        ];
    }

    public function update(array $data, string $uuid): EventResource
    {
        DB::beginTransaction();
        try {
            $event = $this->company()->events()->where('uid', $uuid)->first();

            if (! $event) {
                throw new ApiException('event_not_found', 404);
            }

            $data['st_date'] = $data['st_date'].' '.$data['st_hour'];
            $data['end_date'] = $data['end_date'].' '.$data['end_hour'];

            $this->validateEvent($data['st_date'], $data['end_date'], $uuid);

            $event->update($data);
            DB::commit();

            return EventResource::make($event);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show(string $uuid): EventResource
    {
        $event = $this->company()->events()->where('uid', $uuid)->first();

        if (! $event) {
            throw new ApiException('event_not_found', 404);
        }

        return EventResource::make($event);
    }

    public function delete(string $uuid): bool
    {
        $event = $this->company()->events()->where('uid', $uuid)->first();

        if (! $event) {
            throw new ApiException('event_not_found', 404);
        }

        $st_date = Carbon::parse($event->st_date);

        if ($st_date->isPast()) {
            throw new ApiException('event_is_past', 404);
        }

        $event->delete();

        return true;
    }

    public function getExportEvents(EventFilter $filtrer, EventOrdenator $ordenator): JsonResource
    {
        $events = $this->company()->events()
            ->with(['users', 'tickets'])
            ->filter($filtrer)
            ->sort($ordenator)
            ->get(['name', 'st_date', 'end_date', 'colors', 'likes', 'super_likes']);

        return EventExportResource::collection($events);
    }

    public function getEventsFillable(EventFilter $filter): Collection
    {

        $events = $this->company()->events()
            ->select(['name as label', 'uid as value'])
            ->filter($filter)
            ->get();

        return $events;
    }

    public function getTicketDispatcher(Event $event): Collection
    {
        $tickets_types = $event->tickets()->select('name')->distinct()->get();

        return $tickets_types;
    }

    private function validateEvent($st_date, $end_date, $event): bool
    {
        $st_date = Carbon::parse($st_date);
        $end_date = Carbon::parse($end_date);
        $diff = $st_date->diffInHours($end_date);

        if ($this->company()->checkEveventsInSameTime($st_date, $end_date, $event)) {
            throw new ApiException('event_at_same_time', 409);
        }
        if (! $this->company()->checkEventLimit($st_date, $event)) {
            throw new ApiException('event_limit_reached', 409);
        }

        if ($st_date->lt(now())) {
            throw new ApiException('start_date_past', 409);
        }
        if ($end_date->lt(now())) {
            throw new ApiException('end_date_past', 409);
        }
        if ($diff < 0) {
            throw new ApiException('end_date_before_start', 409);
        }
        if ($diff > 12) {
            throw new ApiException('event_duration_exceeded', 409);
        }
        if ($diff < 2) {
            throw new ApiException('event_duration_too_short', 409);
        }

        return true;
    }
}
