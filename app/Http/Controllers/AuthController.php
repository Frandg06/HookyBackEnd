<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteAuthUserRequest;
use App\Http\Requests\CompleteDataRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthUserReosurce;
use App\Services\AuthService;
use App\Services\ImagesService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \stdClass;

class AuthController extends Controller
{
    public $authService, $userService, $imageService;

    public function __construct(AuthService $authService, UserService $userService, ImagesService $imageService) {
        $this->authService = $authService;
        $this->userService = $userService;
        $this->imageService = $imageService;
    }

    public function register(RegisterRequest $request) 
    {
        try {
            $data = $request->only('name', 'surnames', 'email', 'password');
            $response = $this->authService->register($data);
            return response()->json(["success" => true, "access_token" =>  $response->access_token], 200);
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 400);
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

    public function logout(Request $request) 
    {
        $request->user()->tokens()->delete();
        return response()->json(["success" => true, 'message' => 'Logged out successfully'], 200);
    }

    public function checkAuthentication(Request $request) {
        try {

            $userRequest = $request->user();

            $user = AuthUserReosurce::make($userRequest);

            return response()->json(["resp" => $user, "success" => true], 200); 

        }catch (Exception $e){
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function update(CompleteDataRequest $request) {

        $user = $request->user();
        $data = $request->all();

        try {

            $response = $this->authService->update($user, $data);
            return response()->json(["success" => true, "resp" =>  $response], 200); 
            
        } catch (Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function complete(CompleteAuthUserRequest $request) {
     
        $data = $request->all();
        $user = $request->user(); 
        $info = $this->parseCompleteData($data);   
        $files = $this->parseCompleteFiles($data);
        $interests = $this->parseCompleteInterests($data);

        DB::beginTransaction();
        
        try {
            
            $this->authService->update($user, $info);
            $this->userService->updateInterest($user, $interests);

            if($user->userImages()->count() === 0){
                if(count($files) < 3 || count($files) > 3) throw new \Exception("El usuario solo puede subir 3 imágenes");
                foreach ($files as $file) {
                    $this->imageService->store($user, $file);
                }
            }
            DB::commit();
            return response()->json(["success" => true, "resp" =>  AuthUserReosurce::make($user)], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError($e->getMessage(), 400);
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
            return $this->responseError($e->getMessage(), 400);
        }

    }

    public function changePassword(Request $request) {
        
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = $request->user();
        $data = $request->only('old_password', 'password');

        try {

            $response = $this->authService->changePassword($data, $user);

            return $this->responseSuccess('Password changed successfully');

        } catch (Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    private function parseCompleteData($data) {
        return [
            "born_date" => $data["born_date"],
            "city" => $data["city"],
            "description" => $data["description"],
            "email" => $data["email"],
            "gender_id" => $data["gender_id"],
            "ig" => $data["ig"],
            "interests" => $data["interests"],
            "name" => $data["name"],
            "sexual_orientation_id" => $data["sexual_orientation_id"],
            "surnames" => $data["surnames"],
            "tw" => $data["tw"],
        ];
    }

    private function parseCompleteFiles($data) {
        return [
            $data["userImages0"],
            $data["userImages1"],
            $data["userImages2"],
        ];
    }

    private function parseCompleteInterests($data) {
        return explode(',', $data["interests"]);
    }
}
