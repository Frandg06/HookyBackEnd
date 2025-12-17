<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

final class EventMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->token;

        if (! $token) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.token_not_found')], 404);
        }

        $decryptString = Crypt::decryptString($token);

        $decodedToken = json_decode($decryptString, true);

        $event = Event::where('uid', $decodedToken['event_uid'])->where('code', $decodedToken['code'])->first();

        if (! $event) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.event_not_found')], 404);
        }

        Config::set('app.timezone', $event->company->timezone->name ?? 'Europe/Madrid');
        date_default_timezone_set($event->company->timezone->name ?? 'Europe/Madrid');

        $end_date = Carbon::parse($event->end_date);
        $st_date = Carbon::parse($event->st_date);

        if ($end_date->isPast()) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.event_is_past')], 404);
        }

        if ($st_date->isFuture()) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.event_not_start')], 404);
        }

        $request->event = $event;

        return $next($request);
    }
}
