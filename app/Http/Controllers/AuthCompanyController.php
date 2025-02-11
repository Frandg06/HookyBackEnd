<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Resources\AuthCompanyResource;
use App\Http\Services\AuthCompanyService;
use App\Http\Services\EmailService;
use Illuminate\Http\Request;

class AuthCompanyController extends Controller
{
    protected $authService, $emailService;

    public function __construct(AuthCompanyService $authService, EmailService $emailService) { 
        $this->authService = $authService;
        $this->emailService = $emailService;
    }

    public function register(RegisterCompanyRequest $request) 
    {
        try {
            $data = $request->only('name', 'email', 'phone', 'address', 'city', 'country', 'password');
            
            $response = $this->authService->register($data);

            return response()->json(["success" => true, "access_token" =>  $response->access_token], 200);
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 500);
        }
    }

    public function login(Request $request) 
    {
        try {
            $data = $request->only('email', 'password');
            $response = $this->authService->login($data);

            return response()->json(["success" => true, "access_token" =>  $response->access_token], 200);

        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function me() {
        try {
            $company = request()->user();

            $company = AuthCompanyResource::make($company);

            return response()->json(["resp" => $company, "success" => true], 200); 

        }catch (\Exception $e){
            return $this->responseError($e->getMessage(), 400);
        }
    }
}
