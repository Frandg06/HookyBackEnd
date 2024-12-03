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
        $activeevent = $request->user()->events()->activeEventData();
        $tz = $activeevent->event->timezone;
        $now =  strtotime(Carbon::now($tz));
        $end_date = strtotime(Carbon::parse($activeevent->event->end_date));

        if($now > $end_date)  return response()->json(["error" => true, "message" => "El evento no está activo", "type" => "AuthException"], 401);

        return $next($request);
    }
}
