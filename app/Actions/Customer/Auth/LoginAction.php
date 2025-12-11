<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

final readonly class LoginAction
{
    /**
     * Execute the action.
     */
    public function execute(array $data): string
    {
        return DB::transaction(function () use ($data) {

            $token = JWTAuth::attempt(['email' => $data['email'], 'password' => $data['password']]);

            if (! $token) {
                throw new ApiException('credentials_ko', 401);
            }

            return $token;
        });
    }
}
