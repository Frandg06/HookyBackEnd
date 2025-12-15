<?php

declare(strict_types=1);

namespace App\Traits;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed  $data
     */
    public function successResponse(string $message, $data = [], int $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __($message),
            'content' => $data,
        ], $statusCode);
    }

    public function errorResponse(?string $errorMessage = null, ?string $redirect = null, int $statusCode = 400): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'custom_message' => $errorMessage ? __($errorMessage) : __('i18n.unexpected_error'),
        ];

        $response['redirect'] ??= $redirect;

        return response()->json($response, $statusCode);
    }
}
