<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\EventAttachAction;
use App\Actions\Customer\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

final class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request, RegisterAction $action, EventAttachAction $attachAction)
    {
        $response = $action->execute($request->validated());

        if ($request->filled('event_uid')) {
            $attachAction->execute(JWTAuth::user()->uid, $request->input('event_uid'));
        }

        return $this->successResponse('Registration successful.', ['access_token' => $response]);
    }
}
