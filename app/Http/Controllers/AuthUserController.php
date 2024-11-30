<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CompleteAuthUserRequest;
use App\Http\Requests\CompleteDataRequest;
use App\Http\Resources\AuthUserReosurce;
use App\Services\AuthUserService;
use App\Services\ImagesService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthUserController extends Controller
{
    public $authUserService, $userService, $imageService;

    public function __construct(AuthUserService $authUserService, UserService $userService, ImagesService $imageService) {
        $this->authUserService = $authUserService;
        $this->userService = $userService;
        $this->imageService = $imageService;
    }

    public function update(CompleteDataRequest $request) {

        $user = $request->user();
        $data = $request->all();

        try {

            $response = $this->authUserService->update($user, $data);
            return response()->json(["success" => true, "resp" =>  $response], 200); 
            
        } catch (Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function store(CompleteAuthUserRequest $request) {
     
        $data = $request->all();
        $user = $request->user(); 
        $info = $this->parseCompleteData($data);   
        $files = $this->parseCompleteFiles($data);
        $interests = $this->parseCompleteInterests($data);

        DB::beginTransaction();
        
        try {
            
            $this->authUserService->update($user, $info);
            $this->authUserService->updateInterest($user, $interests);

            if($user->userImages()->count() === 0){
                if(count($files) < 3 || count($files) > 3) throw new \Exception("El usuario solo puede subir 3 imágenes");
                
                foreach ($files as $file) {
                    $this->imageService->store($user, $file);
                }
            }
            DB::commit();
            return response()->json(["success" => true, "resp" =>  AuthUserReosurce::make($user)], 200);
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function setEvent(Request $request, $company_uid) {
        
        try {
            $response = $this->authUserService->setEvent($request, $company_uid);   

            return response()->json([
                "success" => true,
                "resp" => $response
            ], 200);

        }catch (Exception $e){
            return $this->responseError($e->getMessage(), 400);
        }

    }

    public function updatePassword(Request $request) {
        
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = $request->user();
        $data = $request->only('old_password', 'password');

        try {

            $response = $this->authUserService->updatePassword($data, $user);

            return $this->responseSuccess('Password changed successfully');

        } catch (Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function updateInterest(Request $request)
    {
        $request->validate([
            'interests' => 'required|array|min:3'
        ]);

        $user = $request->user();
        $interests = $request->interests;
        try {
            $this->authUserService->updateInterest($user, $interests);

            return response()->json(["success" => true, "resp" => AuthUserReosurce::make($user)], 200);

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 500);

        }
    }

    public function getLikes(Request $request) {
        $user = $request->user();
        try {
            $response = $this->authUserService->getLikes($user);
            return response()->json(["success" => true, "resp" => $response], 200);
        } catch (\Exception $e) {
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
