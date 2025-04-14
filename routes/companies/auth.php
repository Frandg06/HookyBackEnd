<?php

use App\Http\Controllers\Companies\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/email', [AuthController::class, 'passwordReset']);
Route::post('/password/new', [AuthController::class, 'setNewPassword']);
