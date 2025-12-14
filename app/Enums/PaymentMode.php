<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMode: string
{
    case PAYMENT = 'payment';
    case SUBSCRIPTION = 'subscription';

    public function label(): string
    {
        return match ($this) {
            PaymentMode::PAYMENT => 'payment',
            PaymentMode::SUBSCRIPTION => 'subscription',
        };
    }
}
