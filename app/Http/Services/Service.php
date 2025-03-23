<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;

abstract class Service
{
  public function __construct() {}

  public function responseError($message, $code = 400)
  {
    return [
      'message' => __('i18n.' . $message),
      'error' => true,
      'code' => $code
    ];
  }

  public function logError($error, $class, $function)

  {
    Log::error('Error en ' . $class . '->' . $function, ['exception' => $error]);
  }
}
