<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\SocialLoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLoginRequest;

final class SocialLoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SocialLoginRequest $request, string $provider, SocialLoginAction $action)
    {
        $accessToken = $request->input('access_token');
        $result = $action->execute($accessToken, $provider);

        return $this->successResponse('Social login successful', ['access_token' => $result]);
    }
}
