<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ApiException extends Exception
{
    public function __construct($message = 'Error personalizado', $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
