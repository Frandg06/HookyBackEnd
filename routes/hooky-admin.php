<?php
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
    
    Route::post('/register', [AuthController::class, 'registerCompany']);
    Route::post('/login', [AuthController::class, 'loginCompany']);
    
    Route::middleware(['auth:company'])->group(function () {        
        Route::put('/update', [CompanyController::class, 'update']);
        Route::get('/auth', [AuthController::class, 'isCompanyAuth']);
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::post('/event', [EventController::class, 'store']);
        Route::get('/url', [CompanyController::class, 'getLink']);
        Route::post('/tickets', [TicketController::class, 'generateTickets']);
        Route::get('/tickets', [TicketController::class, 'index']);
    });
    
});
