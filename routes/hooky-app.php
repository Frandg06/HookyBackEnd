<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'event'])->group(function () {
    
        
        Route::prefix('user')->group(function () {
            
            Route::put('/password', [AuthUserController::class, 'updatePassword']);
            Route::put('/update', [AuthUserController::class, 'update']);
            Route::post('/complete', [AuthUserController::class, 'completeRegisterData']);
            Route::put('/interest', [AuthUserController::class, 'updateInterest']);

            Route::get('/notifications', [AuthUserController::class, 'getNotifications']);
            Route::post('/notifications/read/{type}', [AuthUserController::class, 'readNotificationsByType']);

            Route::post('/image', [ImageController::class, 'store']);
            Route::post('/image/update', [ImageController::class, 'update']);
            Route::delete('/image/{company_uid}', [ImageController::class, 'delete']);
            Route::delete('/images', [ImageController::class, 'deleteUserImages']);

            Route::post('/redeem', [TicketController::class, 'redeem']);
        });

        Route::prefix('users')->group(function () {

            Route::get('/', [AuthUserController::class, 'getUsers']);
            Route::get('/{uid}', [AuthUserController::class, 'getUser']);
            Route::post('/{uid}', [AuthUserController::class, 'setInteraction'])->middleware('credits');
            Route::get('/confirm/{uid}', [AuthUserController::class, 'getUserToConfirm']);
            
        });

        Route::get('chats', [AuthUserController::class, 'getChats']);

});