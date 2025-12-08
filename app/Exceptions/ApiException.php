<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class ApiException extends Exception
{
    public function __construct($message = 'Error personalizado', $code = 400, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
