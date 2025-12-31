<?php

declare(strict_types=1);

namespace App\Enums\User;

enum GenderEnum: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    public function opposite(): self
    {
        return match ($this) {
            self::MALE => self::FEMALE,
            self::FEMALE => self::MALE,
            self::OTHER => self::OTHER,
        };
    }

    public function same(): self
    {
        return $this;
    }

    public function isMale(): bool
    {
        return $this === self::MALE;
    }

    public function isFemale(): bool
    {
        return $this === self::FEMALE;
    }

    public function isOther(): bool
    {
        return $this === self::OTHER;
    }
}
