<?php

declare(strict_types=1);

use App\Http\Controllers\Customer\Auth\EventAttachController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\LogoutController;
use App\Http\Controllers\Customer\Auth\MeController;
use App\Http\Controllers\Customer\Auth\RegisterController;
use App\Http\Controllers\Customer\Auth\SocialLoginController;
use App\Http\Controllers\Customer\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterController::class)->name('customer.register');
Route::post('/login', LoginController::class)->name('customer.login');
Route::post('/social-login/{provider}', SocialLoginController::class)->name('customer.social.login');
Route::post('/password/email', [AuthController::class, 'passwordReset'])->name('customer.password.email');
Route::put('/password/reset', [AuthController::class, 'setNewPassword'])->name('customer.password.reset');

Route::middleware(['auth:api'])->group(function () {
    Route::post('/login/{event_uid}', EventAttachController::class)->name('customer.login.event');
    Route::post('/logout', LogoutController::class)->name('customer.logout');
    Route::get('/me', MeController::class)->name('customer.me');
});
