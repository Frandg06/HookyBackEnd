<?php

namespace App\Http\Orders;

class TicketOrdenator extends QueryOrdenator
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
        return $this->builder->orderBy('super_likes', $value);
    }

    public function likes(string $value)
    {
        return $this->builder->orderBy('likes', $value);
    }
}
