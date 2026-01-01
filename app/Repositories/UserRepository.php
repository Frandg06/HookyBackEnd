<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Hook;
use App\Models\User;
use App\Models\Settings;
use App\Models\TargetUsers;
use Illuminate\Support\Str;
use App\Enums\InteractionEnum;
use App\Dtos\UpdateSettingsDto;
use App\Models\PasswordResetToken;
use Laravel\Socialite\Two\User as TwoUser;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserRepository
{
    public function findByUuid(string $uuid): ?User
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
        $userModel = User::firstOrCreate(
            ['email' => $user->getEmail()],
            [
                'name' => $user->getName() ?? $user->getNickname() ?? 'No Name',
                'password' => bcrypt(uniqid()),
                'auto_password' => true,
            ]
        );

        $userModel->update([
            'provider_name' => $provider,
            'provider_id' => $user->getId(),
        ]);

        return $userModel->refresh();
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

    public function updateUserAutoPassword(User $user, bool $autoPassword): void
    {
        $user->update([
            'auto_password' => $autoPassword,
        ]);
    }

    public function deletePasswordResetToken(PasswordResetToken $passwordResetToken): void
    {
        $passwordResetToken->delete();
    }

    public function getLikesNotifications(User $user, int $page = 1): LengthAwarePaginator
    {
        return TargetUsers::with([
            'emitter:uid,name',
            'emitter.profilePicture',
            'targetUser:uid,role_id',
        ])->where('target_user_uid', $user->uid)
            ->where('event_uid', $user->event->uid)
            ->whereIn('interaction', InteractionEnum::LikeInteractions())
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'page', $page);
    }

    public function getHooksNotifications(User $user, int $page = 1): LengthAwarePaginator
    {
        return Hook::with([
            'user1:uid,name',
            'user1.profilePicture',
            'user2:uid,name',
            'user2.profilePicture',
            'event:uid,name',
        ])->whereAny(['user1_uid', 'user2_uid'], $user->uid)
            ->where('event_uid', $user->event->uid)
            ->orderBy('created_at', 'desc')
            ->orderBy('uid', 'desc')
            ->paginate(20, ['*'], 'page', $page);
    }

    public function markNotificationsAsReadByType(User $user, string $type): void
    {
        $user->notifications()
            ->where('type', $type)
            ->where('read_at', false)
            ->update(['read_at' => true]);
    }

    public function updateOrCreateUserSettings(string $user_uid, UpdateSettingsDto $data): void
    {
        Settings::updateOrCreate(
            ['user_uid' => $user_uid],
            [
                'new_like_notification' => $data->new_like_notification,
                'new_superlike_notification' => $data->new_superlike_notification,
                'new_message_notification' => $data->new_message_notification,
                'event_start_email' => $data->event_start_email,
            ]
        );
    }
}
