<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request, RegisterAction $action)
    {
        $response = $action->execute($request->validated());

        return $this->successResponse('Registration successful.',['access_token' => $response]);
    }
}
