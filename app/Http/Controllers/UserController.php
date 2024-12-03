<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserReosurce;
use App\Http\Resources\UserResource;
use App\Models\Interaction;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $response = $this->userService->getUsers($user);

        return response()->json(["resp" => $response, "success" => true], 200); 
    }

    public function getUser(Request $request, $uid)
    {
        $user = $request->user();
        
        $isMatch = $user->interactions()->where('interaction_user_uid', $uid)->whereIn('interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])->exists();
        $isNotConfirmed = $user->interactions()->where('interaction_user_uid', $uid)->where('is_confirmed', false)->exists();
        
        if(!$isMatch || !$isNotConfirmed) return response()->json(["success" => false, "message" => "No tienes permisos para ver este usuario", "type" => "RoleException"], 401);

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
}
