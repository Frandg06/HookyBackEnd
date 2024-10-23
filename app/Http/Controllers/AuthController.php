<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteDataRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            return response()->json([
                "success" => true,
                "message" => "Registered successfully",
                "data" => $response
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    public function login(Request $request) 
    {
        try {
            $data = $request->only('email', 'password');
            $response = $this->authService->login($data);
            return response()->json([
                "success" => true,
                "data" => $response,
                "message" => "Logged in successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                "message" => $e->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request) {

        $request->user()->tokens()->delete();
        return response()->json(["success" => true, 'message' => 'Logged out successfully'], 200);

    }

    public function checkAuthentication(Request $request) {
        try {
            $user = $request->user();

            return response()->json([
                "success" => true,
                "data" => $user,
                "message" => "Authenticated",
            ]);

        }catch (Exception $e){
            return response()->json([
                "error" => true,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function update(CompleteDataRequest $request) {

        $user = $request->user();
        $data = $request->only(["instagram", "twitter", "description", "city", "born_date", "gender_id", "sexual_orientation_id"]);
        try {
            $response = $this->authService->completeInfo($user, $data);

            return response()->json([
                "success" => true,
                "data" => $response,
                "message" => "Data complete succesfuly",
            ]);
        } catch (Exception $e) {
            return response()->json([
                "error" => true,
                "message" => $e->getMessage(),
            ]);
        }
    }

    public function setCompany(Request $request) {
        
        try {
            $response = $this->authService->setCompany($request);   
            return response()->json([
                "success" => true,
                'message' => 'Company set successfully',
                "data" => $response
            ], 200);

        }catch (Exception $e){
            return response()->json([
                "error" => true,
                "message" => $e->getMessage(),
            ]);
        }
        

    }
}
