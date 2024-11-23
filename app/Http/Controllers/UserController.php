<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserReosurce;
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

    

    public function setInteraction(Request $request, $id)
    {
        $interaction = $request->interactionId;
        $user = $request->user();

        try {
            $response = $this->userService->setInteraction($user, $id, $interaction);
            return response()->json(["success" => true, "resp" => $response], 200);
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 400);
        }
    }
}
