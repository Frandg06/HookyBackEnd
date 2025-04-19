<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Services\AuthCompanyService;
use App\Http\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    protected $emailService;

    public function __construct(AuthCompanyService $authService, EmailService $emailService)
    {
        $this->authService = $authService;
        $this->emailService = $emailService;
    }

    public function register(RegisterCompanyRequest $request)
    {
        $validated = $request->only('name', 'email', 'phone', 'address', 'city', 'country', 'password');
        $response = $this->authService->register($validated);

        return $this->response($response, 'access_token');
    }

    public function login(Request $request)
    {
        $data = $request->only('email', 'password');
        $response = $this->authService->login($data);

        return $this->response($response, 'access_token');
    }

    public function me()
    {
        $company = company()->resource();

        return $this->response($company);
    }

    public function logout()
    {
        Auth::invalidate(true);
        Auth::logout();

        return response()->json(['success' => true, 'message' => __('i18n.logged_out')], 200);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $response = $this->authService->updatePassword($validated);

        return $this->response($response);
    }

    public function passwordReset(Request $request)
    {
        $email = $request->email;
        $this->authService->passwordReset($email);
        $this->response(true);
    }

    public function setNewPassword(ResetPasswordRequest $request)
    {
        $validated = $request->only('token', 'password');
        $this->authService->setNewPassword($validated);
        $this->response(true);
    }
}
