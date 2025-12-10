<?php

declare(strict_types=1);

namespace App\Enums;

enum SocialProviders: string
{
    case GOOGLE = 'google';
    case APPLE = 'apple';
}
