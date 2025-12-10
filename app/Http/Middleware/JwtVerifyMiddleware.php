<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

final class JwtVerifyMiddleware
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
            throw new AuthenticationException();
        }

        $payload = JWTAuth::setToken($token)->getPayload();

        $user = User::find($payload['uid']);

        if (! $user) {
            throw new AuthenticationException();
        }

        return $next($request);
    }
}
