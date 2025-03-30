<?php

namespace App\Http\Services;

use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Http\Resources\EventResource;
use App\Http\Resources\Exports\EventExportResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class EventService extends Service
{

    public function store(array $data): EventResource|array
    {
        DB::beginTransaction();
        try {
            $data['st_date'] = $data['st_date'] . ' ' . $data['st_hour'];
            $data['end_date'] = $data['end_date'] . ' ' . $data['end_hour'];

            $validator = $this->validateEvent($data['st_date'], $data['end_date'], null);

            if ($validator !== true)  return $validator;

            $event = $this->company()->events()->create($data);

            DB::commit();

            return EventResource::make($event);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('create_event_ko', 409);
        }
    }

    public function getCalendarEvents(array $dates): array
    {
        try {
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
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('get_calendar_events_ko', 409);
        }
    }

    public function getEvents(EventFilter $filtrer, EventOrdenator $ordenator, $limit = 10): array
    {
        try {
            $events = $this->company()->events()
                ->with(['users', 'tickets'])
                ->filter($filtrer)
                ->sort($ordenator)
                ->paginate($limit);

            return  [
                'data' => EventResource::collection($events),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'total' => $events->total(),
                'per_page' => $events->perPage(),
            ];
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('get_events_ko', 409);
        }
    }

    public function update(array $data, string $uuid): EventResource|array
    {
        try {
            $event = $this->company()->events()->where('uid', $uuid)->first();

            if (!$event) $this->responseError('event_not_found', 404);

            $data['st_date'] = $data['st_date'] . ' ' . $data['st_hour'];
            $data['end_date'] = $data['end_date'] . ' ' . $data['end_hour'];
            $validator = $this->validateEvent($data['st_date'], $data['end_date'], $uuid);

            if ($validator !== true)  return $validator;

            $event->update($data);

            return EventResource::make($event);
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('update_event_ko', 500);
        }
    }

    public function show(string $uuid): EventResource|array
    {
        try {
            $event = $this->company()->events()->where('uid', $uuid)->first();

            if (!$event) $this->responseError('event_not_found', 404);

            return EventResource::make($event);
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('update_event_ko', 500);
        }
    }

    public function delete(string $uuid): bool|array
    {
        try {
            $event = $this->company()->events()->where('uid', $uuid)->first();

            if (!$event) $this->responseError('event_not_found', 404);

            $st_date = Carbon::parse($event->st_date);

            if ($st_date->isPast()) $this->responseError('event_is_past', 409);

            $event->delete();

            return true;
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('update_event_ko', 500);
        }
    }

    public function getExportEvents(EventFilter $filtrer, EventOrdenator $ordenator): AnonymousResourceCollection|array
    {
        try {
            $events = $this->company()->events()
                ->with(['users', 'tickets'])
                ->filter($filtrer)
                ->sort($ordenator)
                ->get(['name', 'st_date', 'end_date', 'colors', 'likes', 'super_likes']);

            return  EventExportResource::collection($events);
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('get_events_ko', 500);
        }
    }

    public function getEventsFillable(EventFilter $filter)
    {
        try {
            $events = $this->company()->events()
                ->select(['name as label', 'uid as value'])
                ->filter($filter)
                ->get();

            return $events;
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('get_events_ko', 500);
        }
    }

    private function validateEvent($st_date, $end_date, $event): bool|array
    {
        $now = now();
        $st_date = Carbon::parse($st_date);
        $end_date = Carbon::parse($end_date);
        $diff = $st_date->diffInHours($end_date);

        if ($this->company()->checkEveventsInSameTime($st_date, $end_date, $event)) {
            return $this->responseError('event_at_same_time', 409);
        }
        if (!$this->company()->checkEventLimit($st_date, $event)) {
            return $this->responseError('event_limit_reached', 409);
        }

        if ($st_date < $now) {
            return $this->responseError('start_date_past', 409);
        }
        if ($diff < 0) {
            return $this->responseError('end_date_before_start', 409);
        }
        if ($diff > 12) {
            return $this->responseError('event_duration_exceeded', 409);
        }
        if ($diff < 2) {
            return $this->responseError('event_duration_too_short', 409);
        }

        return true;
    }
}
