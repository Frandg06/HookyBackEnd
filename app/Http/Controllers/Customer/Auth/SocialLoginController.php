<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Exceptions\ApiException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLoginRequest;
use App\Actions\Customer\Auth\EventAttachAction;
use App\Actions\Customer\Auth\SocialLoginAction;

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
            try {
                $attachEventAction->execute(JWTAuth::user()->uid, $request->input('event_uid'));
            } catch (ApiException $e) {
                // Do nothing if event attachment fails
            }
        }

        return $this->successResponse('Social login successful', ['access_token' => $result]);
    }
}
