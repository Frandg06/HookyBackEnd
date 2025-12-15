<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethodTypes: string
{
    case CARD = 'card';
    case PAYPAL = 'paypal';

    public static function values(): array
    {
        return array_map(fn (PaymentMethodTypes $type) => $type->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            PaymentMethodTypes::CARD => 'card',
            PaymentMethodTypes::PAYPAL => 'paypal',
        };
    }
}
