<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        if (!$token) {
            return response()->json(['custom_message' => __('i18n.user_not_login'), 'type' => 'AuthException'], 401);
        }

        $payload = JWTAuth::setToken($token)->getPayload();

        $user = User::find($payload['uid']);

        if (!$user) {
            return response()->json(['custom_message' => __('i18n.user_not_login'), 'type' => 'AuthException'], 401);
        }

        return $next($request);
    }
}
