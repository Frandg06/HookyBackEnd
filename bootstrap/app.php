<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Application;
use App\Console\Commands\DestroyImages;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['middleware' => ['api', 'auth:api']],
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'event' => App\Http\Middleware\CheckEventIsActiveMiddleware::class,
            'credits' => App\Http\Middleware\CheckCreditsMiddleware::class,
            'lang' => App\Http\Middleware\LangMiddleware::class,
            'accept-json' => App\Http\Middleware\AcceptJsonMiddleware::class,
            'jwt.verify.company' => App\Http\Middleware\JwtVerifyCompanyMiddleware::class,
            'auth.event' => App\Http\Middleware\EventMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json(
                [
                    'error' => true,
                    'custom_message' => __('i18n.user_not_login'),
                    'redirect' => '/auth/login',
                ],
                401
            );
        });
        $exceptions->render(function (ApiException $e, Request $request) {
            return response()->json([
                'error' => true,
                'custom_message' => __('i18n.'.$e->getMessage()),
            ], $e->getCode());
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'error' => true,
                'custom_message' => implode(' ', $e->validator->errors()->all()),
            ], 422);
        });

        $exceptions->render(function (Exception $e, Request $request) {
            Log::error($e->getMessage(), ['stack' => $e->getTraceAsString()]);

            return response()->json([
                'error' => true,
                'log' => env('APP_ENV') === 'local' ? $e->getMessage() : null,
                'custom_message' => __('i18n.unexpected_error'),
            ], 500);
        });
    })->withCommands([
        DestroyImages::class,
    ])

    ->create();
