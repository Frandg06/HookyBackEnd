<?php

use App\Http\Controllers\AuthCompanyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
/*
/*
    Todas las peticiones deben de llevar los headers
    - Content-Type: application/json
    - Accept: application/json
*/

Route::prefix('company')->group(function () {
    
    Route::post('/register', [AuthCompanyController::class, 'register']);
    Route::post('/login', [AuthCompanyController::class, 'login']);
    Route::post('/password/email', [AuthCompanyController::class, 'passwordReset']);
    Route::post('/password/new', [AuthCompanyController::class, 'setNewPassword']);
    
    Route::middleware(['auth:company', 'jwt.verify.company'])->group(function () {
        Route::put('/update', [CompanyController::class, 'update']);
        Route::get('/auth', [AuthCompanyController::class, 'me']);
        Route::post('/logout', [AuthCompanyController::class, 'logout']);
        Route::get('/events/calendar', [EventController::class, 'getCalendarEvents']);
        Route::get('/events', [EventController::class, 'getEvents']);
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{uuid}', [EventController::class, 'updateEvent']);
        Route::get('/events/{uuid}', [EventController::class, 'getEventsByUuid']);
        Route::post('/tickets', [TicketController::class, 'generateTickets']);
        Route::get('/tickets', [TicketController::class, 'index']);

    });
    
});
