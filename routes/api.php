<?php

use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'lang'])->group(function () {

    Route::prefix('auth')->group(base_path('routes/hooky-auth.php'));
    Route::prefix('company')->group(base_path('routes/hooky-admin.php'));
    Route::middleware(['auth:api', 'event', 'jwt.verify'])->group(base_path('routes/hooky-app.php'));

    Route::post('/user/complete', [AuthUserController::class, 'completeRegisterData'])->middleware(['auth:api', 'jwt.verify']);

    Route::get('/timezones', [DomainController::class, 'getTimeZones'])->middleware(['auth:company']);
    Route::get('/interests', [DomainController::class, 'getInterests'])->middleware(['auth:api']);

    Route::post('/email/waitlist', [EmailController::class, 'storeWaitlist']);
    Route::delete('/images/all', [ImageController::class, 'deleteAll']);
});
