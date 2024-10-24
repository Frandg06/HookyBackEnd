<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
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
    
    Route::group(['prefix' => 'user'], function () {
        Route::get('/auth', [AuthController::class, 'checkAuthentication']);
        Route::put('/company/{company_id}', [AuthController::class, 'setCompany']);
        Route::put('/update', [AuthController::class, 'update']);
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::post('/images', [ImageController::class, 'store']);
        Route::delete('/images/{uid}', [ImageController::class, 'delete']);
    });



    
});



