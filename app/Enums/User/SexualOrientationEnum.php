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
            self::HETEROSEXUAL => __('i18n.heterosexual'),
            self::GAY => __('i18n.gay'),
            self::LESBIAN => __('i18n.lesbian'),
            self::BISEXUAL => __('i18n.bisexual'),
            self::PREFER_NOT_TO_SAY => __('i18n.prefer_not_to_say'),
        };
    }
}
