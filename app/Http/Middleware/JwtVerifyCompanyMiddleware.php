<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtVerifyCompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        if(!$token) {
            return response()->json(['message' => 'No existe un token', 'type' => 'AuthException'], 401);
        }

        $payload = JWTAuth::setToken($token)->getPayload();
        
        $user = Company::find($payload['uid']);

        if(!$user) {
            return response()->json(['message' => 'No existe una sesion activa', 'type' => 'AuthException'], 401);
        }

        return $next($request);
    }
}
