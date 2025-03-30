<?php

use App\Http\Controllers\CompanyUsersController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:company', 'jwt.verify.company'])->group(function () {
  Route::get('/events', [EventController::class, 'getExportEvents']);
  Route::get('/users/{event_uid}', [CompanyUsersController::class, 'getEventUsersExport']);
  Route::get('/users', [CompanyUsersController::class, 'getUsersExport']);
  Route::get('/tickets', [TicketController::class, 'getTicketsToExport']);
});
