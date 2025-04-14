<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
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
        $user = $request->user();

        $tz = $user->event->timezone;

        $now =  now($tz);

        if ($now->gt($user->event->end_date)) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.event_is_past'), 'type' => 'AuthException'], 401);
        }

        if ($now->lt($user->event->st_date)) {
            return response()->json(['error' => true, 'custom_message' => __('i18n.event_not_start')], 409);
        };

        return $next($request);
    }
}
