<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventService
{

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $company = request()->user();
            $now = now($data['timezone']);
            $st_date = Carbon::parse($data['st_date']);
            $end_date = Carbon::parse($data['end_date']);
            $diff = $st_date->diffInHours($end_date);

            if ($company->checkEveventsInSameTime($st_date, $end_date)) {
                throw new ApiException('event_at_same_time', 409);
            }
            if (!$company->checkEventLimit($st_date)) {
                throw new ApiException('event_limit_reached', 409);
            }

            if ($st_date < $now) {
                throw new ApiException('start_date_past', 409);
            }
            if ($diff < 0) {
                return throw new ApiException('end_date_before_start', 409);
            }
            if ($diff > 12) {
                return throw new ApiException('event_duration_exceeded', 409);
            }
            if ($diff < 2) {
                return throw new ApiException('event_duration_too_short', 409);
            }

            $event = $company->events()->create($data);

            DB::commit();

            return $event;
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('create_event_ko', 500);
        }
    }

    public function getCalendarEvents(array $dates)
    {
        DB::beginTransaction();
        try {
            $company = request()->user();
            $events = [];
            foreach ($dates as $date) {
                $start = Carbon::parse($date)->startOfMonth();
                $end = Carbon::parse($date)->endOfMonth();

                $query = $company->events()
                  ->whereDate('st_date', '>=', $start)
                  ->whereDate('st_date', '<=', $end)
                  ->get(['name', 'st_date as start', 'end_date as end', 'colors', 'uid'])->toArray();

                $events = array_merge($events, $query);
            }

            DB::commit();

            return $events;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('get_calendar_events_ko', 500);
        }
    }
}
