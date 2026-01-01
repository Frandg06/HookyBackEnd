<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

final class RedirectException extends Exception
{
    protected string $redirectUrl;

    public function __construct(string $redirectUrl, string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        $this->redirectUrl = $redirectUrl;
        parent::__construct($message, $code, $previous);
    }

    public static function targetUserNotAvailable(): self
    {
        return new self('/home', __('i18n.not_aviable_user'), 403);
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => true,
            'custom_message' => $this->getMessage(),
            'redirect' => $this->getRedirectUrl(),
        ], $this->getCode() ?: 403);
    }
}
