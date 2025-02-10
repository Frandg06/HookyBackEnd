<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/email', [AuthController::class, 'passwordReset']);
    Route::put('/password/reset', [AuthController::class, 'setNewPassword']);

    Route::middleware(['auth:api', 'event'])->group(function () {      
      Route::get('/me', [AuthController::class, 'me']);
      Route::post('/logout', [AuthController::class, 'logout']);
    });
});
