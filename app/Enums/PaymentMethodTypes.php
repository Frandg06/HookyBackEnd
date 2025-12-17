<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethodTypes: string
{
    case CARD = 'card';
    case AMAZON = 'amazon_pay';
    case REVOLUT = 'revolut_pay';

    public static function values(): array
    {
        return array_map(fn (PaymentMethodTypes $type) => $type->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            PaymentMethodTypes::CARD => 'card',
            PaymentMethodTypes::AMAZON => 'amazon_pay',
            PaymentMethodTypes::REVOLUT => 'revolut_pay',
        };
    }
}
