<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class InsufficientCreditsException extends Exception
{
    public static function likes(?Exception $previous = null): self
    {
        return new self('No tienes suficientes likes para realizar esta acción', 400, $previous);
    }

    public static function superLikes(?Exception $previous = null): self
    {
        return new self('No tienes suficientes super likes para realizar esta acción', 400, $previous);
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => true,
            'custom_message' => $this->getMessage(),
        ], $this->getCode() ?: 400);
    }
}
