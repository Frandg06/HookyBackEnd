<?php

declare(strict_types=1);

namespace App\Enums;

enum InteractionEnum: string
{
    case SUPER_LIKE = 'super_like';
    case LIKE = 'like';
    case DISLIKE = 'dislike';

    public static function fromId(int $id): ?self
    {
        return match ($id) {
            1 => self::SUPER_LIKE,
            2 => self::LIKE,
            3 => self::DISLIKE,
            default => null,
        };
    }

    public function toId(): int
    {
        return match ($this) {
            self::SUPER_LIKE => 1,
            self::LIKE => 2,
            self::DISLIKE => 3,
        };
    }
}
