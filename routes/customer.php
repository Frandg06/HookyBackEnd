<?php

declare(strict_types=1);

use App\Http\Controllers\Customer\Auth\EventAttachController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\LogoutController;
use App\Http\Controllers\Customer\Auth\MeController;
use App\Http\Controllers\Customer\Auth\PasswordResetTokenController;
use App\Http\Controllers\Customer\Auth\RegisterController;
use App\Http\Controllers\Customer\Auth\ResetPasswordController;
use App\Http\Controllers\Customer\Auth\SocialLoginController;
use App\Http\Controllers\Customer\ChatController;
use App\Http\Controllers\Customer\ImageController;
use App\Http\Controllers\Customer\UserController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class)->name('customer.register');
    Route::post('/login', LoginController::class)->name('customer.login');
    Route::post('/social-login/{provider}', SocialLoginController::class)->name('customer.social.login');
    Route::post('/forgot-password', PasswordResetTokenController::class)->name('customer.password.email');
    Route::put('/reset-password/{token}', ResetPasswordController::class)->name('customer.password.reset');

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/login/{event_uid}', EventAttachController::class)->name('customer.login.event');
        Route::post('/logout', LogoutController::class)->name('customer.logout');
        Route::get('/me', MeController::class)->name('customer.me');
    });
});

Route::middleware(['auth:api', 'event'])->group(function () {

    Route::post('/user/complete', [UserController::class, 'completeRegisterData'])->withoutMiddleware('event');

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
