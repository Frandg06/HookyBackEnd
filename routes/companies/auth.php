<?php

use App\Http\Controllers\AuthCompanyController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthCompanyController::class, 'register']);
Route::post('/login', [AuthCompanyController::class, 'login']);
Route::post('/password/email', [AuthCompanyController::class, 'passwordReset']);
Route::post('/password/new', [AuthCompanyController::class, 'setNewPassword']);
