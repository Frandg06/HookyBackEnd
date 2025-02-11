<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthCompanyResource;
use App\Http\Resources\AuthUserReosurce;
use App\Http\Services\EmailService;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Services\AuthService;
use App\Services\ImagesService;
use App\Services\UserService;
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
        } 
    }

    public function registerCompany(RegisterCompanyRequest $request) 
    {
        try {
            $data = $request->only('name', 'email', 'phone', 'address', 'city', 'country', 'password');
            
            $response = $this->authService->registerCompany($data);

            return response()->json(["success" => true, "access_token" =>  $response->access_token], 200);
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 500);
        }
    }

    public function login(Request $request) 
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $response = $this->authService->login($validated, $request->company_uid);

            return response()->json(["success" => true, "access_token" =>  $response->access_token], 200);
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function loginCompany(Request $request) 
    {
        try {
            $data = $request->only('email', 'password');
            $response = $this->authService->loginCompany($data);

            return response()->json(["success" => true, "access_token" =>  $response->access_token], 200);

        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function logout() 
    {
        Auth::invalidate(true);
        Auth::logout();
        return response()->json(["success" => true, 'message' => __('i18n.logged_out')], 200);
    }
    public function me() {
        try {

            $userRequest = Auth::user();

            $user = AuthUserReosurce::make($userRequest);

            return response()->json(["resp" => $user, "success" => true], 200); 

        }catch (Exception $e){
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function isCompanyAuth() {
        try {
            $company = request()->user();

            $company = AuthCompanyResource::make($company);

            return response()->json(["resp" => $company, "success" => true], 200); 

        }catch (Exception $e){
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function passwordReset(Request $request){
        try{

            if(!$request->email) return response()->json(["error" => true, "message" => __('validation.required', ['attribute' => 'email'])], 400);

            $user = User::where('email', $request->email)->first();

            if(!$user){
                return $this->responseError(__('i18n.user_not_found'), 404);
            }
            
            $token = Str::random(60);

            $already_used = PasswordResetToken::where('email', $user->email)->get();

            foreach ($already_used as $token) {
                $token->delete();
            }

            $password_token = PasswordResetToken::create([
                'email' => $user->email,
                'token' => base64_encode($token),
                'expires_at' => now()->addMinutes(15)
            ]);

            $url = config('app.front_url') . '/auth/password/new?token=' . $password_token->token;


            $template = view('emails.recovery_password_app', [
                'link' => $url,
                'name' => $user->name,
            ])->render();

            $this->emailService->sendEmail($user, __('i18n.password_reset_subject'), $template);


            return response()->json(["message" => __('i18n.password_reset_email'), "success" => true], 200);
        }catch (Exception $e){
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function setNewPassword(Request $request){
        try{
            
            $validated = $request->validate([
                'token' => 'required',
                'password' => 'required|min:8|confirmed'
            ]);

            $token_model = PasswordResetToken::where('token', $validated['token'])->first();

            if(!$token_model) return response()->json(["error" => true, "message" => "No existe ningun usuario con ese token"], 404);

            if(now()->greaterThan(Carbon::parse($token_model->expires_at))) return response()->json(["error" => true, "message" => "El token ha expirado"], 404);

            $user = $token_model->user;

            $user->update([
                'password' => bcrypt($validated['password'])
            ]);

            $token_model->delete();

            return response()->json(["message" => __('i18n.password_reset_email'), "success" => true], 200);
        }catch (Exception $e){
            return $this->responseError($e->getMessage(), 400);
        }
    }
}
