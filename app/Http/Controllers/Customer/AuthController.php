<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Services\AuthService;
use App\Http\Services\EmailService;
use App\Http\Services\ImagesService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class AuthController extends Controller
{
    protected $authService;

    protected $userService;

    protected $imageService;

    protected $emailService;

    public function __construct(
        AuthService $authService,
        UserService $userService,
        ImagesService $imageService,
        EmailService $emailService
    ) {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->imageService = $imageService;
        $this->emailService = $emailService;
    }

    public function loginIntoEvent(string $event_uid)
    {
        $response = $this->authService->loginIntoEvent($event_uid);

        return $this->response($response);
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
