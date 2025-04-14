<?php

use App\Models\Company;
use App\Models\User;
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
  Log::error('Error en ' . $class . '->' . $function, ['exception' => $exception]);
}

function debug($log)
{
  Log::info($log);
}
