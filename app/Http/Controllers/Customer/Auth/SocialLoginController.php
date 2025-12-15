<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\EventAttachAction;
use App\Actions\Customer\Auth\SocialLoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

final class SocialLoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SocialLoginRequest $request, string $provider, SocialLoginAction $action, EventAttachAction $attachEventAction)
    {
        $accessToken = $request->input('access_token');
        $result = $action->execute($accessToken, $provider);

        if ($request->filled('event_uid')) {
            $attachEventAction->execute(JWTAuth::user()->uid, $request->input('event_uid'));
        }

        return $this->successResponse('Social login successful', ['access_token' => $result]);
    }
}
