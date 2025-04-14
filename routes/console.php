<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('check:env', function () {
    $this->comment(app()->isProduction() ? 'Production' : 'Development');
})->purpose('Check if the app is in production or development environment');
