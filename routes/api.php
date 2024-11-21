<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckCreditsMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
Todas las peticiones deben de llevar los headers
    - Content-Type: application/json
    - Accept: application/json
    - Authorization: Bearer {token}
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/company', [AuthController::class, 'registerCompany']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    
    Route::group(['prefix' => 'user'], function () {
        
        Route::get('/auth', [AuthController::class, 'checkAuthentication']);
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::put('/password', [AuthController::class, 'changePassword']);

        Route::put('/update', [AuthController::class, 'update']);
        Route::post('/complete', [AuthController::class, 'complete']);
        Route::put('/company/{company_id}', [AuthController::class, 'setCompany']);

        Route::post('/image', [ImageController::class, 'store']);
        Route::post('/image/update', [ImageController::class, 'update']);
        Route::delete('/image/{uid}', [ImageController::class, 'delete']);
        Route::delete('/images', [ImageController::class, 'deleteAllUserImage']);
        Route::put('/interest', [UserController::class, 'updateInterest']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/{id}', [UserController::class, 'setInteraction'])->middleware(CheckCreditsMiddleware::class);
    });

    Route::get('/interests', [DomainController::class, 'getInterests']);
    
    
    
});

Route::delete('/images/all', [ImageController::class, 'deleteAll']);





