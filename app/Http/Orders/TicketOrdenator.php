<?php

declare(strict_types=1);

namespace App\Http\Orders;

final class TicketOrdenator extends QueryOrdenator
{
    public function name(string $value)
    {
        return $this->builder->orderBy('name', $value);
    }

    public function price(string $value)
    {
        return $this->builder->orderBy('price', $value);
    }

    public function redeemed(string $value)
    {
        return $this->builder->orderBy('redeemed', $value);
    }

    public function superlikes(string $value)
    {
        return $this->builder->orderBy('superlikes', $value);
    }

    public function likes(string $value)
    {
        return $this->builder->orderBy('likes', $value);
    }
}
