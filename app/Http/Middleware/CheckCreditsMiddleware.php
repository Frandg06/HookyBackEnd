<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Interaction;
use Closure;
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
        $authUser = user()->event;
        $interaction = $request->interactionId;
        $error = null;

        if ($interaction === Interaction::LIKE_ID && $authUser->likes < 1) {
            $error = true;
        } elseif ($interaction === Interaction::SUPER_LIKE_ID && $authUser->super_likes < 1) {
            $error = true;
        }

        if ($error) {
            return response()->json(['error' => false, 'message' => 'No tienes suficiente creditos para hacer esa interacción'], 400);
        }

        return $next($request);
    }
}
