<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckEventIsActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authEvent = $request->user()->auth_event;

        $tz = $authEvent->event->timezone;

        $now =  now($tz);

        $end_date = Carbon::parse($authEvent->event->end_date);

        if ($now->gt($end_date))  return response()->json(["error" => true, "message" => "El evento no está activo", "type" => "AuthException"], 401);

        if ($now->lt($authEvent->event->st_date)) {
            return response()->json(["error" => true, "message" => "El evento no ha comenzado"], 409);
        };

        return $next($request);
    }
}
