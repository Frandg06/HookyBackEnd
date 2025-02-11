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
    
    Route::middleware(['auth:company'])->group(function () {        
        Route::put('/update', [CompanyController::class, 'update']);
        Route::get('/auth', [AuthCompanyController::class, 'me']);
        Route::post('/logout', [AuthCompanyController::class, 'logout']);
        Route::post('/event', [EventController::class, 'store']);
        Route::post('/tickets', [TicketController::class, 'generateTickets']);
        Route::get('/tickets', [TicketController::class, 'index']);
    });
    
});
