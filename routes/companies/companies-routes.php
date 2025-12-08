<?php

declare(strict_types=1);

use App\Http\Controllers\Companies\AuthController;
use App\Http\Controllers\Companies\ChartsController;
use App\Http\Controllers\Companies\CompanyController;
use App\Http\Controllers\Companies\CustomersController;
use App\Http\Controllers\Companies\EventController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.event'])->group(function () {
    Route::post('/event/dispatcher', [EventController::class, 'getTicketDispatcher']);
    Route::post('/event/dispatcher/qr', [TicketController::class, 'getQrCode']);
});

Route::middleware(['auth:company', 'jwt.verify.company'])->group(function () {
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::get('/auth', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::put('/update', [CompanyController::class, 'update']);
    Route::get('/events/calendar', [EventController::class, 'getCalendarEvents']);
    Route::get('/events', [EventController::class, 'getEvents']);
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{uuid}', [EventController::class, 'updateEvent']);
    Route::get('/events/{uuid}', [EventController::class, 'getEventsByUuid']);
    Route::delete('/events/{uuid}', [EventController::class, 'deleteEventById']);
    Route::post('/tickets/{uuid}', [TicketController::class, 'generateTickets']);
    Route::get('/tickets', [TicketController::class, 'getTickets']);

    Route::prefix('users')->group(function () {
        Route::get('', [CustomersController::class, 'getUsers']);
        Route::get('/{event_uid}', [CustomersController::class, 'getEventUsers']);
    });

    Route::prefix('fillable')->group(function () {
        Route::get('/events', [EventController::class, 'getEventsFillable']);
    });

    Route::get('/charts/users_incomes', [ChartsController::class, 'getUserIncomesData']);
    Route::get('/charts/recent_entries', [ChartsController::class, 'getUsersEntries']);
    Route::get('/charts/avg_age', [ChartsController::class, 'getAverageAge']);
    Route::get('/charts/users_incomes/{uid}', [ChartsController::class, 'getUserIncomesFromEvent']);
});
