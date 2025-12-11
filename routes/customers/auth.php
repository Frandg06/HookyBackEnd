<?php

declare(strict_types=1);

use App\Http\Controllers\Customers\Auth\LoginController;
use App\Http\Controllers\Customers\Auth\RegisterController;
use App\Http\Controllers\Customers\Auth\SocialLoginController;
use App\Http\Controllers\Customers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)->name('customer.register');
Route::post('/login', LoginController::class)->name('customer.login');
Route::post('/social-login/{provider}', SocialLoginController::class)->name('customer.social.login');
Route::post('/password/email', [AuthController::class, 'passwordReset'])->name('customer.password.email');
Route::put('/password/reset', [AuthController::class, 'setNewPassword'])->name('customer.password.reset');

Route::middleware(['auth:api', 'jwt.verify'])->group(function () {
    Route::post('/login/{event_uid}', [AuthController::class, 'loginIntoEvent'])->name('customer.login.event');
    Route::post('/logout', [AuthController::class, 'logout'])->name('customer.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('customer.me');
});
