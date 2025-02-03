<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api', 'event'])->group(function () {
    
        Route::prefix('user')->group(function () {
            
            Route::get('/me', [AuthController::class, 'me']);
            Route::get('/logout', [AuthController::class, 'logout']);
            
            Route::put('/password', [AuthUserController::class, 'updatePassword']);
            Route::put('/update', [AuthUserController::class, 'update']);
            Route::post('/complete', [AuthUserController::class, 'store']);
            Route::put('/interest', [AuthUserController::class, 'updateInterest']);
            Route::get('/notifications', [AuthUserController::class, 'getNotifications']);
            Route::post('/notifications/read/{type}', [AuthUserController::class, 'readNotificationsByType']);

            Route::post('/image', [ImageController::class, 'store']);
            Route::post('/image/update', [ImageController::class, 'update']);
            Route::delete('/image/{company_uid}', [ImageController::class, 'delete']);
            Route::delete('/images', [ImageController::class, 'deleteAllUserImage']);

            Route::post('/redeem', [TicketController::class, 'redeem']);

        });

        Route::prefix('users')->group(function () {

            Route::get('/', [UserController::class, 'index']);
            Route::post('/{uid}', [UserController::class, 'setInteraction'])->middleware('credits');
            Route::get('/confirm/{uid}', [UserController::class, 'getUserToConfirm']);
            Route::get('/{uid}', [UserController::class, 'getUser']);
            
        });

});