<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class NotFoundException extends Exception
{
    public function __construct(string $resource = 'recurso', int $code = 0, ?Throwable $previous = null)
    {
        $message = "No se ha encontrado el {$resource} solicitado.";

        return parent::__construct($message, $code, $previous);
    }

    public static function chat(): self
    {
        return new self('chat');
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => true,
            'custom_message' => $this->getMessage(),
        ], $this->getCode() ?: 404);
    }
}
