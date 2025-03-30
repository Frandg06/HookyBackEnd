<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChartUserAndIncomesResource;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    public function getUserIncomesData(Request $request)
    {
        $events = $this->company()->events()
            ->withCount('users2')
            ->orderBy('st_date', 'desc')
            ->take($request->limit ?? 15)
            ->get()
            ->reverse();

        $events = ChartUserAndIncomesResource::make($events);
        return $this->response($events);
    }

    public function getUsersEntries(Request $request)
    {
        $start = now()->subDay($request->days ?? 1)->startOfHour();
        $end = now()->startOfHour();

        // Paso 1: Obtener datos desde la BBDD
        $rawCounts = DB::table('user_events')
            ->join('events', 'user_events.event_uid', '=', 'events.uid')
            ->where('events.company_uid', $this->company()->uid)
            ->whereNotNull('user_events.logged_at')
            ->whereBetween('logged_at', [$start, $end])
            ->selectRaw("date_trunc('hour', user_events.\"logged_at\") as hour, COUNT(*) as count")
            ->groupBy('hour')
            ->orderBy('hour', 'desc')
            ->get();

        // Paso 2: Convertimos a array asociativo con clave `hour`
        $countsByHour = $rawCounts->pluck('count', 'hour')->mapWithKeys(function ($count, $hour) {
            return [Carbon::parse($hour)->format('Y-m-d H:00:00') => $count];
        });

        // Paso 3: Generar todas las horas del rango
        $period = CarbonPeriod::create($start, '1 hour', $end);

        $filledCounts = collect();
        foreach ($period as $hour) {
            $formatted = $hour->format('Y-m-d H:00:00');
            $filledCounts->push([
                'hour' => $formatted,
                'count' => $countsByHour[$formatted] ?? 0,
            ]);
        }

        return $this->response([
            'labels' => $filledCounts->pluck('hour')->map(function ($date) {
                return Carbon::parse($date)->format('d/m H:i');
            }),
            'data' => $filledCounts->pluck('count'),
        ]);
    }

    public function getAverageAge(Request $request)
    {
        $event = $this->company()->last_event;


        $ageGroups = DB::table('user_events')
            ->join('events', 'user_events.event_uid', '=', 'events.uid')
            ->join('users', 'user_events.user_uid', '=', 'users.uid')
            ->where('events.uid', $event->uid)
            ->whereNotNull('users.born_date')
            ->selectRaw("
                CASE
                    WHEN EXTRACT(YEAR FROM AGE(users.born_date)) BETWEEN 18 AND 22 THEN '18-22'
                    WHEN EXTRACT(YEAR FROM AGE(users.born_date)) BETWEEN 23 AND 27 THEN '23-27'
                    WHEN EXTRACT(YEAR FROM AGE(users.born_date)) BETWEEN 28 AND 32 THEN '28-32'
                    WHEN EXTRACT(YEAR FROM AGE(users.born_date)) BETWEEN 33 AND 37 THEN '33-37'
                    ELSE '38+'
                END AS age_group,
                COUNT(*) AS total
            ")
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();

        return $this->response([
            'labels' => $ageGroups->pluck('age_group'),
            'data' => $ageGroups->pluck('total'),
            'total' => $ageGroups->sum('total'),
        ]);
    }
}
