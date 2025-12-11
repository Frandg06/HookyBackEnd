<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\LoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request, LoginAction $action)
    {
        $response = $action->execute($request->validated());

        return $this->successResponse('Login successful.',['access_token' => $response]);
    }
}
