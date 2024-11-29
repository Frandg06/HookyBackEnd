<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckCreditsMiddleware;
use Illuminate\Support\Facades\Route;
/*
Todas las peticiones deben de llevar los headers
    - Content-Type: application/json
    - Accept: application/json
    - Authorization: Bearer {token}
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['prefix' => 'company'], function () {
    Route::post('/register', [AuthController::class, 'registerCompany']);
    Route::post('/login', [AuthController::class, 'loginCompany']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    
    Route::group(['prefix' => 'user'], function () {
        
        Route::get('/auth', [AuthController::class, 'isUserAuth']);
        Route::get('/logout', [AuthController::class, 'logout']);
        
        Route::put('/password', [AuthUserController::class, 'updatePassword']);
        Route::put('/update', [AuthUserController::class, 'update']);
        Route::post('/complete', [AuthUserController::class, 'store']);
        Route::put('/event/{uid}', [AuthUserController::class, 'setEvent']); 
        Route::put('/interest', [AuthUserController::class, 'updateInterest']);

        Route::post('/image', [ImageController::class, 'store']);
        Route::post('/image/update', [ImageController::class, 'update']);
        Route::delete('/image/{company_uid}', [ImageController::class, 'delete']);
        Route::delete('/images', [ImageController::class, 'deleteAllUserImage']);

        Route::post('/redeem', [TicketController::class, 'redeem']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/{id}', [UserController::class, 'setInteraction'])->middleware(CheckCreditsMiddleware::class);
    });

    Route::group(['prefix' => 'company'], function () {
        Route::put('/update', [CompanyController::class, 'update']);
        Route::get('/auth', [AuthController::class, 'isCompanyAuth']);
        Route::get('/logout', [AuthController::class, 'logoutCompany']);
        Route::post('/event', [EventController::class, 'store']);
        Route::get('/url', [CompanyController::class, 'getLink']);
        Route::post('/tickets', [TicketController::class, 'generateTickets']);
        Route::get('/tickets', [TicketController::class, 'index']);
       
    });

    Route::get('/interests', [DomainController::class, 'getInterests']);
    Route::get('/timezones', [DomainController::class, 'getTimeZones']);
     
});


Route::post('/email', [EmailController::class, 'test']);
Route::post('/email/waitlist', [EmailController::class, 'storeWaitlist']);
Route::delete('/images/all', [ImageController::class, 'deleteAll']);





