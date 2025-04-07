<?php

use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {

    Route::put('/password', [AuthUserController::class, 'updatePassword']);
    Route::put('/update', [AuthUserController::class, 'update']);

    Route::get('/notifications', [AuthUserController::class, 'getNotifications']);
    Route::post('/notifications/read/{type}', [AuthUserController::class, 'readNotificationsByType']);

    Route::post('/image', [ImageController::class, 'store']);
    Route::post('/image/update', [ImageController::class, 'update']);
    Route::delete('/image/{company_uid}', [ImageController::class, 'delete']);
    Route::delete('/images', [ImageController::class, 'deleteUserImages']);

    Route::post('/redeem', [TicketController::class, 'redeem']);

    Route::get('/chat', [ChatController::class, 'retrieve']);
    Route::post('/chat/{uid}/send', [ChatController::class, 'sendMessage']);
    Route::put('/chat/{uid}/read', [ChatController::class, 'readMessage']);
    Route::get('/chat/{uid}', [ChatController::class, 'show']);
});

Route::prefix('users')->group(function () {

    Route::get('/', [AuthUserController::class, 'getUsers']);
    Route::get('/{uid}', [AuthUserController::class, 'getUser']);
    Route::post('/{uid}', [AuthUserController::class, 'setInteraction'])->middleware('credits');
    Route::get('/confirm/{uid}', [AuthUserController::class, 'getUserToConfirm']);
});
