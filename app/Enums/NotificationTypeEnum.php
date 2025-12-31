<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationTypeEnum: string
{
    case LIKE = 'like';
    case SUPERLIKE = 'superlike';
    case HOOK = 'hook';
    case MESSAGE = 'message';

    public function toId(): int
    {
        return match ($this) {
            NotificationTypeEnum::LIKE => 1,
            NotificationTypeEnum::SUPERLIKE => 2,
            NotificationTypeEnum::HOOK => 3,
            NotificationTypeEnum::MESSAGE => 4,
        };
    }

    public function label(): string
    {
        return match ($this) {
            NotificationTypeEnum::LIKE => 'like',
            NotificationTypeEnum::SUPERLIKE => 'superlike',
            NotificationTypeEnum::HOOK => 'hook',
            NotificationTypeEnum::MESSAGE => 'message',
        };
    }
}
