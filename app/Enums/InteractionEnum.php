<?php

declare(strict_types=1);

namespace App\Enums;

enum InteractionEnum: string
{
    case SUPERLIKE = 'superlike';
    case LIKE = 'like';
    case DISLIKE = 'dislike';

    public static function LikeInteractions(): array
    {
        return [self::LIKE, self::SUPERLIKE];
    }

    public function isLike(): bool
    {
        return in_array($this, [self::LIKE, self::SUPERLIKE], true);
    }

    public function isDislike(): bool
    {
        return $this === self::DISLIKE;
    }
}
