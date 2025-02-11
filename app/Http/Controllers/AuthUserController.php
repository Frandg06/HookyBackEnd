<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CompleteAuthUserRequest;
use App\Http\Requests\CompleteDataRequest;
use App\Http\Resources\AuthUserReosurce;
use App\Http\Resources\MessageListResource;
use App\Http\Resources\UserResource;
use App\Http\Services\NotificationService;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\NotificationsType;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersInteraction;
use App\Services\AuthUserService;
use App\Services\ImagesService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthUserController extends Controller
{
    protected $authUserService, $userService, $imageService, $notificationService;

    public function __construct(AuthUserService $authUserService, UserService $userService, ImagesService $imageService, NotificationService $notificationService) 
    {
        $this->authUserService = $authUserService;
        $this->userService = $userService;
        $this->imageService = $imageService;
        $this->notificationService = $notificationService;
    }

    public function update(CompleteDataRequest $request) 
    {

        $data = $request->all();

        try {

            $response = $this->authUserService->update($data);
            return response()->json(["success" => true, "resp" =>  $response], 200); 
            
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function completeRegisterData(CompleteAuthUserRequest $request) 
    {
     
        $data = $request->all();
        $user = $request->user(); 
        $info = $this->parseCompleteData($data);   
        $files = $this->parseCompleteFiles($data);
        $interests = $this->parseCompleteInterests($data);

        DB::beginTransaction();
        
        try {
            $this->authUserService->update($info);
            $this->authUserService->updateInterest($user, $interests);

            if($user->userImages()->count() === 0){
                if(count($files) < 3 || count($files) > 3) throw new \Exception("El usuario solo puede subir 3 imágenes");
                
                foreach ($files as $file) {
                    $this->imageService->store($user, $file);
                }
            }
            DB::commit();
            return response()->json(["success" => true, "resp" =>  AuthUserReosurce::make($user)], 200);
        } catch (\Exception $e) {
            Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
            DB::rollBack();
            return $this->responseError($e->getMessage(), 400);
        }
    }

    public function updatePassword(Request $request) 
    {
        
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = $request->user();
        $data = $request->only('old_password', 'password');

        try {

            $response = $this->authUserService->updatePassword($data, $user);

            return $this->responseSuccess('Password changed successfully');

        } catch (\Exception $e) {
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

    public function getNotifications(Request $request) 
    {
        $user = $request->user();
        
        try {
            
            $response = $this->authUserService->getNotifications($user);
            return response()->json(["success" => true, "resp" => $response], 200);

        } catch (\Exception $e) {
            
            return $this->responseError($e->getMessage(), 400);

        }
    }

    public function readNotificationsByType(Request $request, $type) 
    {
        $user = $request->user();

        try {

            $response = $this->notificationService->readNotificationsByType($user, $type);
            return response()->json(["success" => true, "resp" => $response], 200);

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 400);

        }
    }

    public function getUsers(Request $request)
    {
        $user = $request->user();
        $response = $this->userService->getUsers($user);

        return response()->json(["resp" => $response, "success" => true], 200); 
    }

    public function getUserToConfirm(Request $request, $uid)
    {
        $user = $request->user();
        
        if($user->role_id != Role::ROLE_PREMIUM) return response()->json(["success" => false, "message" => "No tienes permisos para ver este usuario", "type" => "RoleException"], 401);

        $isLike = UsersInteraction::where('user_uid', $uid)
                    ->where('interaction_user_uid', $user->uid)
                    ->whereIn('interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
                    ->where('event_uid', $user->event_uid)
                    ->exists();
        
        if(!$isLike) return response()->json(["success" => false, "message" => "No tienes permisos para ver este usuario", "type" => "RoleException"], 401);

        $user = User::where('uid', $uid)->first();

        $response = UserResource::make($user);

        return response()->json(["resp" => $response, "success" => true], 200);
    }

    public function getUser(Request $request, $uid)
    {
        $user = $request->user();

        // En el futuro si es hook cuando interacciona se crearea un chat ebntoinces esto lo que hara sera comprobar si hay chat abierto (a espensas de microservicios)
        $isHook = UsersInteraction::checkHook($user->uid, $uid, $user->event_uid);
                    
        if(!$isHook) return response()->json(["success" => false, "message" => "No tienes permisos para ver este usuario", "type" => "RoleException"], 401);

        $user = User::where('uid', $uid)->first();

        $response = UserResource::make($user);

        return response()->json(["resp" => $response, "success" => true], 200);
    }

    public function setInteraction(Request $request, $uid)
    {
        $interaction = $request->interactionId;
        $user = $request->user();

        try {
            $response = $this->userService->setInteraction($user, $uid, $interaction);
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

    private function parseCompleteFiles($data) 
    {
        return [
            $data["userImages0"],
            $data["userImages1"],
            $data["userImages2"],
        ];
    }

    private function parseCompleteInterests($data) 
    {
        return explode(',', $data["interests"]);
    }
}
