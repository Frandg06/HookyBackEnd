<?php

declare(strict_types=1);

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

abstract class Service extends Controller
{
    final public function responseError($message, $code = 400): array
    {
        return [
            'message' => __('i18n.'.$message),
            'error' => true,
            'code' => $code,
        ];
    }

    final public function logError($error, $class, $function)
    {
        Log::error('Error en '.$class.'->'.$function, ['exception' => $error]);
    }
}
