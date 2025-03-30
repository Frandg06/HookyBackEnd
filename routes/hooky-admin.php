<?php

use App\Http\Controllers\AuthCompanyController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUsersController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

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
    Route::delete('/events/{uuid}', [EventController::class, 'deleteEventById']);
    Route::post('/tickets/{uuid}', [TicketController::class, 'generateTickets']);
    Route::get('/tickets', [TicketController::class, 'getTickets']);

    Route::prefix('users')->group(function () {
        Route::get('', [CompanyUsersController::class, 'getUsers']);
        Route::get('/{event_uid}', [CompanyUsersController::class, 'getEventUsers']);
    });

    Route::prefix('fillable')->group(function () {
        Route::get('/events', [EventController::class, 'getEventsFillable']);
        Route::get('/users/{event_uid}', [CompanyUsersController::class, 'getEventUsersExport']);
        Route::get('/users', [CompanyUsersController::class, 'getUsersExport']);
    });
    Route::get('/charts/users_incomes', [ChartsController::class, 'getUserIncomesData']);
});
