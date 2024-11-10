<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserReosurce;
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

    public function updateInterest(Request $request)
    {
        $request->validate([
            'interests' => 'required|array|min:3'
        ]);

        $user = $request->user();
        $interests = $request->interests;
        try {
            $this->userService->updateInterest($user, $interests);

            return response()->json(["success" => true, "resp" => UserReosurce::make($user)], 200);

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 500);

        }
    }
}
