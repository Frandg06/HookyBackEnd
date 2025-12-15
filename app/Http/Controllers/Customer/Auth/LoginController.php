<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\EventAttachAction;
use App\Actions\Customer\Auth\LoginAction;
use App\Actions\Customer\Events\GetActiveEventByCompanyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

final class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request, LoginAction $action, GetActiveEventByCompanyAction $eventAction, EventAttachAction $attachAction)
    {
        $response = $action->execute($request->validated());

        if ($request->filled('company_uid')) {
            $event = $eventAction->execute($request->input('company_uid'));
            $attachAction->execute(JWTAuth::user()->uid, $event->uid);
        }

        return $this->successResponse('Login successful.', ['access_token' => $response]);
    }
}
