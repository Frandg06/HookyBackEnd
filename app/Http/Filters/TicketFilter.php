<?php

namespace App\Http\Filters;

use App\Http\Filters\QueryFilter;

class TicketFilter extends QueryFilter
{
    public function name(string $value)
    {
        return $this->builder->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($value) . '%']);
    }
    public function eventUid(string $value)
    {
        return $this->builder->where('event_uid', $value);
    }

    public function priceMin($value)
    {
        return $this->builder->where('price', '>=', $value);
    }

    public function priceMax($value)
    {
        return $this->builder->where('price', '<=', $value);
    }

    public function likesMin($value)
    {
        return $this->builder->where('likes', '>=', $value);
    }

    public function likesMax($value)
    {
        return $this->builder->where('likes', '<=', $value);
    }

    public function superlikesMin($value)
    {
        return $this->builder->where('super_likes', '>=', $value);
    }

    public function superlikesMax($value)
    {
        return $this->builder->where('super_likes', '<=', $value);
    }

    public function redeemed(bool $value)
    {
        return $this->builder->where('redeemed', $value);
    }
}
