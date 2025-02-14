<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/email', [AuthController::class, 'passwordReset']);
    Route::put('/password/reset', [AuthController::class, 'setNewPassword']);

    Route::middleware(['auth:api', 'jwt.verify'])->group(function () {
      Route::post('/logout', [AuthController::class, 'logout'])->middleware(['event']);
      Route::get('/me', [AuthController::class, 'me']);
    });
});
