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

    public static function label(): string
    {
        return match (self::cases()) {
            self::ESSENTIAL => 'Essential',
            self::ADVANCED => 'Advanced',
            self::PROFESSIONAL => 'Professional',
            self::PREMIUM => 'Premium',
            self::VIP => 'VIP',
        };
    }
}
