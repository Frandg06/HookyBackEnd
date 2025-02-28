<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Services\AuthCompanyService;
use App\Http\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthCompanyController extends Controller
{
    protected $authService, $emailService;

    public function __construct(AuthCompanyService $authService, EmailService $emailService) { 
        $this->authService = $authService;
        $this->emailService = $emailService;
    }

    public function register(RegisterCompanyRequest $request) 
    {  
        $validated = $request->only('name', 'email', 'phone', 'address', 'city', 'country', 'password');    
        $response = $this->authService->register($validated);
        
        return response()->json(["success" => true, "access_token" =>  $response], 200);
    }

    public function login(Request $request) 
    {
        $data = $request->only('email', 'password');
        $response = $this->authService->login($data);

        return response()->json(["success" => true, "access_token" =>  $response], 200);
    }

    public function me() 
    {
        try {
            $company = request()->user()->resource();
            return response()->json(["resp" => $company, "success" => true], 200); 
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }

    public function logout() 
    {
        Auth::invalidate(true);
        Auth::logout();

        return response()->json(["success" => true, 'message' => __('i18n.logged_out')], 200);
    }

    public function passwordReset(Request $request)
    {
        $email = $request->email;        
        $this->authService->passwordReset($email);

        return response()->json(["message" => __('i18n.password_reset_email'), "success" => true], 200);
    }

    public function setNewPassword(ResetPasswordRequest $request) 
    {
        $validated = $request->only('token', 'password');
        $this->authService->setNewPassword($validated);

        return response()->json(["message" => __('i18n.password_reset_email'), "success" => true], 200);
    }
}
