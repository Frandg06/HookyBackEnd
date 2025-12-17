<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Log;

function user(): User
{
    return auth('api')->user();
}

function company(): Company
{
    return auth('company')->user();
}

function log_error($exception, $class, $function)
{
    Log::error('Error en '.$class.'->'.$function, ['exception' => $exception]);
}

function debug($log)
{
    Log::info($log);
}
