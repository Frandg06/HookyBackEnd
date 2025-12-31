<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\Notification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;

final readonly class ReadNotificationsByTypeAction
{
    public function __construct(private readonly UserRepository $user_repository) {}

    public function execute(User $user, string $type): array
    {
        return DB::transaction(function () use ($user, $type) {
            $this->user_repository->markNotificationsAsReadByType($user, $type);

            return $user->unread_notifications;
        });
    }
}
