<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthCompanyResource;
use App\Http\Resources\AuthUserReosurce;
use App\Models\Company;
use App\Services\AuthService;
use App\Services\ImagesService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService, $userService, $imageService;

    public function __construct(AuthService $authService, UserService $userService, ImagesService $imageService) {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->imageService = $imageService;
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

        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 400);
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
                'password' => 'required'
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

    public function logout(Request $request) 
    {
        $request->user()->tokens()->delete();
        return response()->json(["success" => true, 'message' => 'Logged out successfully'], 200);
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

    public function isCompanyAuth(Request $request) {
        try {
            $company = $request->user();

            $company = AuthCompanyResource::make($company);

            return response()->json(["resp" => $company, "success" => true], 200); 

        }catch (Exception $e){
            return $this->responseError($e->getMessage(), 400);
        }
    }
}
