<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserReosurce;
use App\Http\Resources\UserResource;
use App\Models\Interaction;
use App\Models\User;
use App\Models\UsersInteraction;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function getUserToConfirm(Request $request, $uid)
    {
        $user = $request->user();

        if($user->role_id != User::ROLE_PREMIUM) return response()->json(["success" => false, "message" => "No tienes permisos para ver este usuario", "type" => "RoleException"], 401);

        $isLike = UsersInteraction::where('user_uid', $uid)
                    ->where('interaction_user_uid', $user->uid)
                    ->whereIn('interaction_id', [Interaction::LIKE_ID, Interaction::SUPER_LIKE_ID])
                    ->where('event_uid', $user->event_uid)
                    ->whereExists(function ($query) use ($user, $uid) {
                        $query->select(DB::raw(1))
                            ->from('users_interactions as ui')
                            ->where('ui.user_uid', $user->uid)
                            ->where('ui.interaction_user_uid', $uid)
                            ->where('ui.event_uid', $user->event_uid)
                            ->where(function ($subquery) {
                                $subquery->where('ui.interaction_id', Interaction::DISLIKE_ID) 
                                    ->orWhereNull('ui.interaction_id');
                            });
                    })
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
}
