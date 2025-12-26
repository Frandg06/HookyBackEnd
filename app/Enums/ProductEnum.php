<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductEnum: string
{
    case ESSENTIAL = 'Essential';
    case ADVANCED = 'Advanced';
    case PROFESSIONAL = 'Professional';
    case PREMIUM = 'Premium';
    case VIP = 'VIP';

    public function label(): string
    {
        return match ($this) {
            self::ESSENTIAL => 'Essential',
            self::ADVANCED => 'Advanced',
            self::PROFESSIONAL => 'Professional',
            self::PREMIUM => 'Premium',
            self::VIP => 'VIP',
        };
    }
}
