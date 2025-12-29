<?php

declare(strict_types=1);

namespace App\Enums\User;

enum SexualOrientationEnum: string
{
    case HETEROSEXUAL = 'heterosexual';
    case GAY = 'gay';
    case LESBIAN = 'lesbian';
    case BISEXUAL = 'bisexual';
    case PREFER_NOT_TO_SAY = 'prefer_not_to_say';

    public static function label(): string
    {
        return match (self::class) {
            self::HETEROSEXUAL => 'heterosexual',
            self::GAY => 'gay',
            self::LESBIAN => 'lesbian',
            self::BISEXUAL => 'bisexual',
            self::PREFER_NOT_TO_SAY => 'prefer_not_to_say',
        };
    }

    public function isHomosexual(): bool
    {
        return in_array($this, [self::GAY, self::LESBIAN]);
    }

    public function isBisexual(): bool
    {
        return $this === self::BISEXUAL;
    }

    public function isHeterosexual(): bool
    {
        return $this === self::HETEROSEXUAL;
    }
}
