<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;

function user(): User
{
  return request()->user();
}

function company(): User
{
  return request()->user();
}

function log_error($exception, $class, $function)
{
  Log::error('Error en ' . $class . '->' . $function, ['exception' => $exception]);
}
