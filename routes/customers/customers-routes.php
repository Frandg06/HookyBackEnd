<?php

declare(strict_types=1);

use App\Http\Controllers\Customers\ChatController;
use App\Http\Controllers\Customers\ImageController;
use App\Http\Controllers\Customers\UserController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'event', 'jwt.verify'])->group(function () {

    Route::prefix('user')->group(function () {
        Route::put('/password', [UserController::class, 'updatePassword']);
        Route::put('/update', [UserController::class, 'update']);

        Route::get('/notifications', [UserController::class, 'getNotifications']);
        Route::post('/notifications/read/{type}', [UserController::class, 'readNotificationsByType']);

        Route::post('/images', [ImageController::class, 'store']);
        Route::post('/images/{uid}', [ImageController::class, 'update']);
        Route::delete('/images/{uid}', [ImageController::class, 'delete']);
        Route::delete('/images', [ImageController::class, 'deleteUserImages']);

        Route::post('/redeem', [TicketController::class, 'redeem']);

        Route::get('/chat', [ChatController::class, 'retrieve']);
        Route::post('/chat/{uid}/send', [ChatController::class, 'sendMessage']);
        Route::put('/chat/{uid}/read', [ChatController::class, 'readMessage']);
        Route::get('/chat/{uid}', [ChatController::class, 'show']);
    });

    Route::prefix('users')->group(function () {

        Route::get('/', [UserController::class, 'retrieveTargetUsers']);
        Route::get('/{uid}', [UserController::class, 'showTargetUser']);
        Route::post('/{uid}', [UserController::class, 'setInteraction'])->middleware('credits');
        Route::get('/confirm/{uid}', [UserController::class, 'getUserToConfirm']);
    });
});

Route::middleware(['auth:api', 'jwt.verify'])->post('/user/complete', [UserController::class, 'completeRegisterData']);
