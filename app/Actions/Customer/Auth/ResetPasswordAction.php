<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use Carbon\Carbon;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;

final readonly class ResetPasswordAction
{
    public function __construct(private readonly UserRepository $userRepository) {}

    /**
     * Execute the action.
     */
    public function execute(string $token, string $password): void
    {
        DB::transaction(function () use ($token, $password) {
            $passwordResetToken = $this->userRepository->getPasswordResetToken($token);

            if (now()->greaterThan(Carbon::parse($passwordResetToken->expires_at))) {
                throw new ApiException('token_expired', 404);
            }

            $this->userRepository->updateUserPassword($passwordResetToken->user, $password);
            $this->userRepository->deletePasswordResetToken($passwordResetToken);

        });
    }
}
