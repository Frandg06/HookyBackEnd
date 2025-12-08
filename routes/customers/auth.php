<?php

declare(strict_types=1);

use App\Http\Controllers\Customers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('customer.register');
Route::post('/login', [AuthController::class, 'login'])->name('customer.login');
Route::post('/social-login/{provider}', [AuthController::class, 'socialLogin'])->name('customer.social.login');
Route::post('/password/email', [AuthController::class, 'passwordReset'])->name('customer.password.email');
Route::put('/password/reset', [AuthController::class, 'setNewPassword'])->name('customer.password.reset');

Route::middleware(['auth:api', 'jwt.verify'])->group(function () {
    Route::post('/login/{event_uid}', [AuthController::class, 'loginIntoEvent'])->name('customer.login.event');
    Route::post('/logout', [AuthController::class, 'logout'])->name('customer.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('customer.me');
});
