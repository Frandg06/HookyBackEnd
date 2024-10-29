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
    public function updateInterest(Request $request)
    {
        $request->validate([
            'interests' => 'required|array|min:3'
        ]);

        $user = $request->user();
        $interests = $request->interests;
        try {
            $this->userService->updateInterest($user, $interests);
            return $this->responseSuccess('Interests updated successfully', UserReosurce::make($user));

        } catch (\Exception $e) {

            return $this->responseError($e->getMessage(), 500);

        }
    }
}
