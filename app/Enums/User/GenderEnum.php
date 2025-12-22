<?php

declare(strict_types=1);

namespace App\Enums\User;

enum GenderEnum: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';
}
