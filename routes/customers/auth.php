<?php

use App\Http\Controllers\Customers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/email', [AuthController::class, 'passwordReset']);
Route::put('/password/reset', [AuthController::class, 'setNewPassword']);

Route::middleware(['auth:api', 'jwt.verify'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
