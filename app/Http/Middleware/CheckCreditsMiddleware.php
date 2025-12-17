<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckCreditsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $interaction = $request->interactionId;
        $error = null;

        if ($request->user()->isPremium()) {
            return $next($request);
        }

        if ($interaction === Interaction::LIKE_ID && $request->user()->likes < 1) {
            $error = true;
        } elseif ($interaction === Interaction::SUPER_LIKE_ID && $request->user()->super_likes < 1) {
            $error = true;
        }

        if ($error) {
            return response()->json(['error' => false, 'message' => 'No tienes suficiente creditos para hacer esa interacción'], 400);
        }

        return $next($request);
    }
}
