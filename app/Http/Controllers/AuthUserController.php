<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;

use App\Http\Requests\CompleteAuthUserRequest;
use App\Http\Requests\CompleteDataRequest;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\MessageListResource;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthUserService;
use App\Http\Services\ImagesService;
use App\Http\Services\NotificationService;
use App\Http\Services\UserService;
use App\Models\Interaction;
use App\Models\Notification;
use App\Models\NotificationsType;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersInteraction;
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
        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function completeRegisterData(CompleteAuthUserRequest $request) 
    {     
        $data = $request->all();
        $info = $this->parseCompleteData($data);   
        $files = $this->parseCompleteFiles($data);
        $interests = $this->parseCompleteInterests($data);

        try {
            $response = $this->authUserService->completeRegisterData($info, $files, $interests);
            
            return response()->json(["success" => true, "resp" => $response], 200);
        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function updatePassword(Request $request) 
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $data = $request->only('old_password', 'password');

        try {
            $response =$this->authUserService->updatePassword($data);

            return response()->json(["success" => true, "resp" => $response], 200);
        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function updateInterest(Request $request)
    {
        $request->validate([
            'interests' => 'required|array|min:3'
        ]);

        $interests = $request->interests;

        try {
            $response = $this->authUserService->updateInterest($interests);

            return response()->json(["success" => true, "resp" => $response], 200);
        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function getNotifications() 
    {
        $response = $this->authUserService->getNotifications();
        return response()->json(["success" => true, "resp" => $response], 200);
    }

    public function readNotificationsByType($type) 
    {
        $response = $this->notificationService->readNotificationsByType($type);
        return response()->json(["success" => true, "resp" => $response], 200);
    }

    public function getUsers()
    {
        $response = $this->userService->getUsers();
        return response()->json(["resp" => $response, "success" => true], 200); 
    }

    public function getUserToConfirm(Request $request, $uid)
    {
        $user = $request->user();
        
        if($user->role_id != Role::ROLE_PREMIUM) return response()->json(["success" => false, "message" => "No tienes permisos para ver este usuario", "type" => "RoleException"], 401);

        $isLike = UsersInteraction::checkIsLike($uid, $user);
        
        if(!$isLike) return response()->json(["success" => false, "message" => "No tienes permisos para ver este usuario", "type" => "RoleException"], 401);

        $user = User::where('uid', $uid)->first();

        return response()->json(["resp" => $user->resource(), "success" => true], 200);
    }

    public function getUser(Request $request, $uid)
    {
        $user = $request->user();

        $isHook = UsersInteraction::checkHook($user->uid, $uid, $user->event_uid);
                    
        if(!$isHook) return response()->json(["success" => false, "message" => "No tienes permisos para ver este usuario", "type" => "RoleException"], 401);

        $user = User::where('uid', $uid)->first();

        return response()->json(["resp" => $user->resource(), "success" => true], 200);
    }

    public function setInteraction(Request $request, $uid)
    {
        $interaction = $request->interactionId;

        $response = $this->userService->setInteraction($uid, $interaction);
        return response()->json(["success" => true, "resp" => $response], 200);
        
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
