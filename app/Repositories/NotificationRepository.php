<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dtos\InteractionDto;
use App\Models\Notification;
use App\Enums\NotificationTypeEnum;

final class NotificationRepository
{
    /**
     * Create a new class instance.
     */
    public function create(InteractionDto $dto, NotificationTypeEnum $type): Notification
    {
        return Notification::create([
            'event_uid' => $dto->event_uid,
            'user_uid' => $dto->target_user_uid,
            'type' => $type->value,
        ]);
    }

    public function storeBoth(InteractionDto $dto, NotificationTypeEnum $type): void
    {
        // Notify target user
        $this->create($dto, $type);

        // Notify emitter user
        Notification::create([
            'event_uid' => $dto->event_uid,
            'user_uid' => $dto->user_uid,
            'type' => $type->value,
        ]);
    }
}
