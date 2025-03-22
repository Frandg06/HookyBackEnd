<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:company', 'jwt.verify.company'])->group(function () {
  Route::get('/events', [EventController::class, 'getExportEvents']);
});
