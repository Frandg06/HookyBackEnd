<?php

use App\Http\Controllers\Companies\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->name('company.register');
Route::post('/login', [AuthController::class, 'login'])->name('company.login');
Route::post('/password/email', [AuthController::class, 'passwordReset'])->name('company.password.email');
Route::post('/password/new', [AuthController::class, 'setNewPassword'])->name('company.password.reset');
