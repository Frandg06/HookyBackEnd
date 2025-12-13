<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckEventIsActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $event = $user->event;

        if (! $event) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.event_not_found'), 'redirect' => '/home'], 404);
        }

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
