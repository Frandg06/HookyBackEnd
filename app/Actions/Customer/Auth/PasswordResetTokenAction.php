<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Http\Services\EmailService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

final readonly class PasswordResetTokenAction
{
    public function __construct(private readonly UserRepository $userRepository, private readonly EmailService $emailService) {}

    /**
     * Execute the action.
     */
    public function execute(string $email)
    {
        return DB::transaction(function () use ($email) {
            $user = $this->userRepository->getUserByEmail($email);

            $passwordResetToken = $this->userRepository->generatePasswordResetTokenRequest($email);

            $this->emailService->sendPasswordResetEmail($user, $passwordResetToken->token);
        });
    }
}
