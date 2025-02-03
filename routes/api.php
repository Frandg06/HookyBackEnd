<?php


use App\Http\Controllers\DomainController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

/*
    Todas las peticiones deben de llevar los headers
    - Content-Type: application/json
    - Accept: application/json
    - Authorization: Bearer {token}
*/  
Route::middleware(['api', 'lang'])->group(function () {
    require __DIR__ . '/hooky-admin.php';
    require __DIR__ . '/hooky-app.php';
    Route::get('/timezones', [DomainController::class, 'getTimeZones'])->middleware(['auth:company']);
    Route::get('/interests', [DomainController::class, 'getInterests'])->middleware(['auth:api']);
    
    Route::post('/email', [EmailController::class, 'test']);
    Route::post('/email/waitlist', [EmailController::class, 'storeWaitlist']);
    Route::delete('/images/all', [ImageController::class, 'deleteAll']);
});





