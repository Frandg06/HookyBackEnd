<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use \stdClass;

class AuthController extends Controller
{
    public $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request) 
    {
        try {
            $data = $request->only('name', 'email', 'password');
            $response = $this->authService->register($data);
            return $response;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function login(Request $request) 
    {
        try {
            $data = $request->only('email', 'password');
            $response = $this->authService->login($data);
            return $response;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function logout(Request $request) {

        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);

    }

    public function checkAuthentication(Request $request) {
        return $request->user();
    }

    public function setCompany(Request $request) {
        
        $request->user()->company_id = $request->company_id;
        $request->user()->save();

        return response()->json(['message' => 'Company set successfully'], 200);
    }
}
