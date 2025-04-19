<?php

use App\Http\Controllers\Companies\CustomersController;
use App\Http\Controllers\Companies\EventController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:company', 'jwt.verify.company'])->group(function () {
    Route::get('/events', [EventController::class, 'getExportEvents']);
    Route::get('/users/{event_uid}', [CustomersController::class, 'getEventUsersExport']);
    Route::get('/users', [CustomersController::class, 'getUsersExport']);
    Route::get('/tickets', [TicketController::class, 'getTicketsToExport']);
});
