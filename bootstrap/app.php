<?php

use App\Console\Commands\DestroyImages;
use App\Exceptions\ApiException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'event' => \App\Http\Middleware\CheckEventIsActiveMiddleware::class,
            'credits' => \App\Http\Middleware\CheckCreditsMiddleware::class,
            'lang' => \App\Http\Middleware\LangMiddleware::class,
            'jwt.verify' => \App\Http\Middleware\JwtVerifyMiddleware::class,
            'jwt.verify.company' => \App\Http\Middleware\JwtVerifyCompanyMiddleware::class,
            'auth.event' => \App\Http\Middleware\EventMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json(['custom_message' => __('i18n.user_not_login'), 'type' => 'AuthException'], 401);
        });
        $exceptions->render(function (ApiException $e, Request $request) {
            return response()->json([
                'error' => true,
                'custom_message' => __('i18n.' . $e->getMessage()),
            ], $e->getCode());
        });
        $exceptions->render(function (ValidationException $e, Request $request) {
            return response()->json([
                'error' => true,
                'message' => $e->validator->errors()
            ], 422);
        });
        $exceptions->render(function (Exception $e, Request $request) {
            return response()->json([
                'error' => true,
                'custom_message' => __('i18n.unexpected_error'),
            ], 500);
        });
    })->withCommands([
        DestroyImages::class,
    ])

    ->create();
