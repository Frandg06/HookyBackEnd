<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User as TwoUser;

final class UserRepository
{
    public function getUserByUuid(string $uuid): ?User
    {
        return User::find($uuid);
    }

    public function updateUserCompany(User $user, string $company_uuid): User
    {
        $user->update([
            'company_uid' => $company_uuid,
        ]);

        return $user->refresh();
    }

    public function updateOrCreateUserFromSocialLogin(TwoUser $user, string $provider): ?User
    {
        return User::updateOrCreate([
            'email' => $user->getEmail(),
        ], [
            'provider_name' => $provider,
            'provider_id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => bcrypt(uniqid()),
        ]);
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function generatePasswordResetTokenRequest(string $email): PasswordResetToken
    {
        return PasswordResetToken::updateOrCreate([
            'email' => $email,
        ], [
            'token' => base64_encode(Str::random(64)),
            'expires_at' => now()->addMinutes(15),
        ]);
    }

    public function getPasswordResetToken(string $token): ?PasswordResetToken
    {
        return PasswordResetToken::where('token', $token)->first();
    }

    public function updateUserPassword(User $user, string $password): void
    {
        $user->update([
            'password' => bcrypt($password),
        ]);
    }

    public function deletePasswordResetToken(PasswordResetToken $passwordResetToken): void
    {
        $passwordResetToken->delete();
    }
}
