<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteDataRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserReosurce;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
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
            $data = $request->only('name', 'surnames', 'email', 'password');
            $response = $this->authService->register($data);
            return $this->responseSuccess('Register in successfully', $response['user'], ["access_token" =>  $response['access_token']]);
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
            return $this->responseSuccess('Logged in successfully', $response['user'], ["access_token" =>  $response['access_token']]);
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function logout(Request $request) 
    {

        $request->user()->tokens()->delete();
        return response()->json(["success" => true, 'message' => 'Logged out successfully'], 200);

    }

    public function checkAuthentication(Request $request) {
        try {
            
            $userRequest = $request->user();

            $user = UserReosurce::make($userRequest);

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
        $data = $request->all();
        try {
            $response = $this->authService->update($user, $data);

            return $this->responseSuccess('Data complete succesfuly', $response);
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

    public function changePassword(Request $request) {
        try {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
            $user = $request->user();
            $data = $request->only('old_password', 'password');


            $response = $this->authService->changePassword($data, $user);
            
            return response()->json([
                "success" => true,
                "data" => $response,
                "message" => "Password changed successfully",
            ]);
        } catch (Exception $e) {
            return response()->json([
                "error" => true,
                "message" => $e->getMessage(),
            ]);
        }
    }
}
