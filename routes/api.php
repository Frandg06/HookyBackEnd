<?php

declare(strict_types=1);

use App\Http\Controllers\Companies\DomainController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'lang', 'accept-json'])->group(function () {

    Route::prefix('customers')->group(base_path('routes/customer.php'));

    Route::prefix('company')->group(function () {
        Route::prefix('auth')->group(base_path('routes/companies/auth.php'));
        Route::prefix('')->group(base_path('routes/companies/companies-routes.php'));
        Route::prefix('export')->group(base_path('routes/export.php'));
        Route::get('/timezones', [DomainController::class, 'getTimeZones'])->middleware(['auth:company']);
    });
});
