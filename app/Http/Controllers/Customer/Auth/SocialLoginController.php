<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\EventAttachAction;
use App\Actions\Customer\Auth\SocialLoginAction;
use App\Actions\Customer\Events\GetActiveEventByCompanyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLoginRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

final class SocialLoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SocialLoginRequest $request, string $provider, SocialLoginAction $action, GetActiveEventByCompanyAction $getEventAction, EventAttachAction $attachEventAction)
    {
        $accessToken = $request->input('access_token');
        $result = $action->execute($accessToken, $provider);

        if ($request->filled('company_uid')) {
            $event = $getEventAction->execute($request->input('company_uid'));
            $attachEventAction->execute(JWTAuth::user()->uid, $event->uid);
        }

        return $this->successResponse('Social login successful', ['access_token' => $result]);
    }
}
