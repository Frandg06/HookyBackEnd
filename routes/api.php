<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
Todas las peticiones deben de llevar los headers
    - Content-Type: application/json
    - Accept: application/json
    - Authorization: Bearer {token}
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user/auth', [AuthController::class, 'checkAuthentication']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/user/company', [AuthController::class, 'setCompany']);
});



