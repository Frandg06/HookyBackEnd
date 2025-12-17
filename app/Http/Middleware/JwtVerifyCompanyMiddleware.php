<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Models\Company;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

final class JwtVerifyCompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['custom_message' => 'No existe una sesion activa', 'destroy_session' => true], 401);
        }

        $payload = JWTAuth::setToken($token)->getPayload();

        $user = Company::find($payload['uid']);

        if (! $user) {
            return response()->json(['custom_message' => 'No existe una sesion activa', 'destroy_session' => true], 401);
        }

        Config::set('app.timezone', $user->timezone->name ?? 'Europe/Madrid');
        date_default_timezone_set($user->timezone->name ?? 'Europe/Madrid');

        return $next($request);
    }
}
