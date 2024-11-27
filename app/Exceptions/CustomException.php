<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CustomException extends Exception
{
  public function __construct($message = "Error personalizado", $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}