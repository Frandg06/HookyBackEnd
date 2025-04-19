<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        $user = $request->user();

        $tz = $user->event->timezone;

        $now = now($tz);

        if ($user->event->is_finished) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.event_is_past'), 'redirect' => '/no-event'], 401);
        }

        if ($now->lt($user->event->st_date)) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.event_not_start'), 'redirect' => '/no-event'], 409);
        }

        return $next($request);
    }
}
