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
use App\Http\Controllers\Customer\Event\GetEventsCityController;
use App\Http\Controllers\Customer\Event\GetEventsController;
use App\Http\Controllers\Customer\Image\OrderImageController;
use App\Http\Controllers\Customer\ImageController;
use App\Http\Controllers\Customer\UserController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/auth/register', RegisterController::class)->name('customer.register');
Route::post('/auth/login', LoginController::class)->name('customer.login');
Route::post('/auth/social-login/{provider}', SocialLoginController::class)->name('customer.social.login');

// Password reset routes
Route::post('/auth/forgot-password', PasswordResetTokenController::class)->name('customer.password.email');
Route::put('/auth/reset-password/{token}', ResetPasswordController::class)->name('customer.password.reset');

Route::middleware(['auth:api'])->group(function () {
    // Auth routes
    Route::post('auth/login/{event_uid}', EventAttachController::class)->name('customer.login.event');
    Route::post('auth/logout', LogoutController::class)->name('customer.logout');
    Route::get('auth/me', MeController::class)->name('customer.me');

    // Event routes
    Route::get('/event', GetEventsController::class);
    Route::get('/event/cities', GetEventsCityController::class);

    // User routes
    Route::post('/user/complete', [UserController::class, 'completeRegisterData']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::post('/user/images', [ImageController::class, 'store']);
    Route::post('/user/images/{uid}', [ImageController::class, 'update']);
    Route::delete('/user/images/{uid}', [ImageController::class, 'delete']);
    Route::delete('/user/images', [ImageController::class, 'deleteUserImages']);
    Route::put('/user/images/{uid}/order', OrderImageController::class);

    Route::middleware('event')->group(function () {
        // Notifications routes
        Route::get('/notifications', [UserController::class, 'getNotifications']);
        Route::post('/notifications/read/{type}', [UserController::class, 'readNotificationsByType']);

        // Chat routes
        Route::get('/chat', [ChatController::class, 'retrieve']);
        Route::post('/chat/{uid}/send', [ChatController::class, 'sendMessage']);
        Route::put('/chat/{uid}/read', [ChatController::class, 'readMessage']);
        Route::get('/chat/{uid}', [ChatController::class, 'show']);

        // Ticket routes
        Route::post('/redeem', [TicketController::class, 'redeem']);

        Route::get('/target-users', [UserController::class, 'retrieveTargetUsers']);
        Route::get('/target-users/{uid}', [UserController::class, 'showTargetUser']);
        Route::post('/target-users/{uid}', [UserController::class, 'setInteraction'])->middleware('credits');
        Route::get('/target-users/confirm/{uid}', [UserController::class, 'getUserToConfirm']);
    });
});
