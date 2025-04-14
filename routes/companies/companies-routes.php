<?php

use App\Http\Controllers\AuthCompanyController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUsersController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


Route::post('/event/dispatcher', [EventController::class, 'getTicketDispatcher'])->middleware('auth.event');
Route::post('/event/dispatcher/qr', [TicketController::class, 'getQrCode'])->middleware('auth.event');

Route::middleware(['auth:company', 'jwt.verify.company'])->group(function () {
    Route::post('/update-password', [AuthCompanyController::class, 'updatePassword']);
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
    Route::get('/charts/recent_entries', [ChartsController::class, 'getUsersEntries']);
    Route::get('/charts/avg_age', [ChartsController::class, 'getAverageAge']);
    Route::get('/charts/users_incomes/{uid}', [ChartsController::class, 'getUserIncomesFromEvent']);
});
