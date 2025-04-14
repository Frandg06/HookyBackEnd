<?php

use App\Http\Controllers\Companies\DomainController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'lang'])->group(function () {

    Route::prefix('customers')->group(function () {
        Route::prefix('auth')->group(base_path('routes/customers/auth.php'));
        Route::prefix('')->group(base_path('routes/customers/customers-routes.php'));
    });

    Route::prefix('company')->group(function () {
        Route::prefix('auth')->group(base_path('routes/companies/auth.php'));
        Route::prefix('')->group(base_path('routes/companies/companies-routes.php'));
        Route::prefix('export')->group(base_path('routes/export.php'));
        Route::get('/timezones', [DomainController::class, 'getTimeZones'])->middleware(['auth:company']);
    });




    Route::delete('/images/all', [ImageController::class, 'deleteAll']);
});
