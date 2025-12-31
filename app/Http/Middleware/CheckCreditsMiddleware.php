<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\InteractionEnum;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\InsufficientCreditsException;

final class CheckCreditsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $interaction = $request->segments()[count($request->segments()) - 1];

        if ($request->user()->isPremium()) {
            return $next($request);
        }

        if ($interaction === InteractionEnum::LIKE && $request->user()->likes < 1) {
            throw InsufficientCreditsException::likes();
        }

        if ($interaction === InteractionEnum::SUPERLIKE && $request->user()->super_likes < 1) {
            throw InsufficientCreditsException::superLikes();
        }

        return $next($request);
    }
}
