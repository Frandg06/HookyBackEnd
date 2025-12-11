<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    protected $authService;

    protected $userService;

    protected $imageService;

    protected $emailService;

    public function __construct(
        AuthService $authService,
    ) {
        $this->authService = $authService;
    }

    public function passwordReset(Request $request)
    {
        $email = $request->email;
        $this->authService->passwordReset($email);

        return response()->json(['message' => __('i18n.password_reset_email'), 'success' => true], 200);
    }

    public function setNewPassword(ResetPasswordRequest $request)
    {
        $validated = $request->only('token', 'password');
        $this->authService->setNewPassword($validated);

        return response()->json(['message' => __('i18n.password_reset_email'), 'success' => true], 200);
    }
}
