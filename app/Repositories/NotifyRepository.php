<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\InteractionDto;
use App\Models\Notification;

final class NotifyRepository
{
    /**
     * Create a new class instance.
     */
    public function create(InteractionDto $dto, int $typeId): Notification
    {
        return Notification::create([
            'event_uid' => $dto->event_uid,
            'user_uid' => $dto->target_user_uid,
            'emitter_uid' => $dto->user_uid,
            'type_id' => $typeId,
        ]);
    }

    public function createBoth(InteractionDto $dto, int $typeId): void
    {
        // Notify target user
        $this->create($dto, $typeId);

        // Notify emitter user
        Notification::create([
            'event_uid' => $dto->event_uid,
            'user_uid' => $dto->user_uid,
            'emitter_uid' => $dto->target_user_uid,
            'type_id' => $typeId,
        ]);
    }
}
