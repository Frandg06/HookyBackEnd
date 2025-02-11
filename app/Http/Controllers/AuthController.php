<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\AuthUserReosurce;
use App\Http\Services\AuthService;
use App\Http\Services\EmailService;
use App\Http\Services\ImagesService;
use App\Http\Services\UserService;
use App\Models\PasswordResetToken;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $authService, $userService, $imageService, $emailService;

    public function __construct(AuthService $authService, UserService $userService, ImagesService $imageService, EmailService $emailService) { 
        $this->authService = $authService;
        $this->userService = $userService;
        $this->imageService = $imageService;
        $this->emailService = $emailService;
    }

    public function register(RegisterRequest $request) 
    {
        try {
            $data = $request->only('name', 'surnames', 'email', 'password', 'company_uid');

            $response = $this->authService->register($data);

            return response()->json([
                "success" => true, 
                "access_token" =>  $response->access_token
            ], 200);

        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function login(LoginRequest $request) 
    {
        try {
            $credentials = $request->only('email', 'password');
            $company_uid = $request->company_uid;
            
            $response = $this->authService->login($credentials, $company_uid);

            return response()->json(["success" => true, "access_token" => $response], 200);
        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function logout() 
    {
        Auth::invalidate(true);
        Auth::logout();
        return response()->json(["success" => true, 'message' => __('i18n.logged_out')], 200);
    }

    public function me() 
    {
        try {

            $userRequest = Auth::user();

            $user = AuthUserReosurce::make($userRequest);

            return response()->json(["resp" => $user, "success" => true], 200); 

        }catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function passwordReset(Request $request)
    {
        try{

            $email = $request->email;

            if(!$email) return response()->json(["error" => true, "message" => __('validation.required', ['attribute' => 'email'])], 400);

            $this->authService->passwordReset($email);

            return response()->json(["message" => __('i18n.password_reset_email'), "success" => true], 200);
        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function setNewPassword(ResetPasswordRequest $request) 
    {
        try{

            $validated = $request->only('token', 'password');

            $this->authService->setNewPassword($validated);

            return response()->json(["message" => __('i18n.password_reset_email'), "success" => true], 200);
        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }
}
