<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\Auth\MeController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\LogoutController;
use App\Http\Controllers\Customer\Auth\RegisterController;
use App\Http\Controllers\Customer\Chat\GetChatsController;
use App\Http\Controllers\Customer\Chat\ShowChatController;
use App\Http\Controllers\Customer\Event\GetEventController;
use App\Http\Controllers\Customer\Stripe\PaymentController;
use App\Http\Controllers\Customer\Event\GetEventsController;
use App\Http\Controllers\Customer\Image\SwapImageController;
use App\Http\Controllers\Customer\TargetUser\LikeController;
use App\Http\Controllers\Customer\User\UpdateUserController;
use App\Http\Controllers\Customer\Auth\EventAttachController;
use App\Http\Controllers\Customer\Auth\SocialLoginController;
use App\Http\Controllers\Customer\Chat\SendMessageController;
use App\Http\Controllers\Customer\Image\ImageStoreController;
use App\Http\Controllers\Customer\Image\OrderImageController;
use App\Http\Controllers\Customer\Image\DeleteImageController;
use App\Http\Controllers\Customer\Auth\ResetPasswordController;
use App\Http\Controllers\Customer\TargetUser\DislikeController;
use App\Http\Controllers\Customer\Chat\MarkChatAsReadController;
use App\Http\Controllers\Customer\Event\GetEventsCityController;
use App\Http\Controllers\Customer\Stripe\MakeCheckoutController;
use App\Http\Controllers\Customer\Ticket\RedeemTicketController;
use App\Http\Controllers\Customer\User\ShowTargetUserController;
use App\Http\Controllers\Customer\User\UpdatePasswordController;
use App\Http\Controllers\Customer\Event\GetEventsGuestController;
use App\Http\Controllers\Customer\TargetUser\SuperlikeController;
use App\Http\Controllers\Customer\Image\ClearUserImagesController;
use App\Http\Controllers\Customer\User\CompleteUserDataController;
use App\Http\Controllers\Customer\Auth\PasswordResetTokenController;
use App\Http\Controllers\Customer\User\NotifyStartOfEventController;
use App\Http\Controllers\Customer\TargetUser\GetTargetUsersController;
use App\Http\Controllers\Customer\TargetUser\CheckPendingMatchController;
use App\Http\Controllers\Customer\User\Notification\GetHookNotificationsController;
use App\Http\Controllers\Customer\User\Notification\GetLikeNotificationsController;
use App\Http\Controllers\Customer\User\Notification\ReadNotificationsByTypeController;

// Auth routes
Route::post('/auth/register', RegisterController::class)->name('customer.register');
Route::post('/auth/login', LoginController::class)->name('customer.login');
Route::post('/auth/social-login/{provider}', SocialLoginController::class)->name('customer.social.login');

// Password reset routes
Route::post('/auth/forgot-password', PasswordResetTokenController::class)->name('customer.password.email');
Route::put('/auth/reset-password/{token}', ResetPasswordController::class)->name('customer.password.reset');

// Public Event routes
Route::get('/event/guest', GetEventsGuestController::class);
Route::get('/event/cities', GetEventsCityController::class);
Route::get('/event/{slug}', GetEventController::class);

Route::middleware(['auth:api'])->group(function () {
    // Auth routes
    Route::post('auth/login/{event_uid}', EventAttachController::class)->name('customer.login.event');
    Route::post('auth/logout', LogoutController::class)->name('customer.logout');
    Route::get('auth/me', MeController::class)->name('customer.me');

    // Event routes
    Route::get('/event', GetEventsController::class);
    Route::post('/event/{uid}/notify', NotifyStartOfEventController::class);

    // User routes
    Route::post('/user/complete', CompleteUserDataController::class);
    Route::put('/user/update', UpdateUserController::class);

    Route::put('/user/password', UpdatePasswordController::class);

    Route::post('/user/images', ImageStoreController::class);
    Route::post('/user/images/{uid}', SwapImageController::class);
    Route::delete('/user/images/{uid}', DeleteImageController::class);
    Route::delete('/user/images', ClearUserImagesController::class);
    Route::put('/user/images/{uid}/order', OrderImageController::class);

    Route::get('/stripe/checkout', MakeCheckoutController::class)->name('customer.stripe.checkout');
    Route::get('/stripe/checkout/status', PaymentController::class)->name('customer.stripe.payment');

    Route::middleware('event')->group(function () {
        // Notifications routes
        Route::get('/notifications/like', GetLikeNotificationsController::class);
        Route::get('/notifications/hook', GetHookNotificationsController::class);
        Route::post('/notifications/{type}/read', ReadNotificationsByTypeController::class);

        // Chat routes
        Route::get('/chat', GetChatsController::class);
        Route::post('/chat/{uid}/send', SendMessageController::class);
        Route::put('/chat/{uid}/read', MarkChatAsReadController::class);
        Route::get('/chat/{uid}', ShowChatController::class);

        // Ticket routes
        Route::post('/redeem', RedeemTicketController::class);

        Route::get('/target-users', GetTargetUsersController::class);
        Route::get('/target-users/{uid}', ShowTargetUserController::class);
        Route::post('{event_uid}/target-users/{target_user_uid}/like', LikeController::class)->middleware('credits');
        Route::post('{event_uid}/target-users/{target_user_uid}/superlike', SuperlikeController::class)->middleware('credits');
        Route::post('{event_uid}/target-users/{target_user_uid}/dislike', DislikeController::class);
        Route::get('/target-users/confirm/{uid}', CheckPendingMatchController::class);
    });
});
