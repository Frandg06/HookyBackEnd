<?php

declare(strict_types=1);

namespace App\Http\Orders;

final class UserOrdenator extends QueryOrdenator
{
    public function name(string $order = 'asc')
    {
        $this->builder->orderBy('name', $order);
    }

    public function age(string $order = 'asc')
    {
        $str = $order === 'asc' ? 'desc' : 'asc';
        $this->builder->orderBy('born_date', $str);
    }

    public function gender(string $order = 'asc')
    {
        $this->builder->orderBy('gender_id', $order);
    }

    public function email(string $order = 'asc')
    {
        $this->builder->orderBy('email', $order);
    }

    public function consuption(string $order = 'asc')
    {
        $this->builder->withSum(['tickets' => function ($query) {
            $query->where('redeemed', true);
        }], 'price')->orderBy('tickets_sum_price', $order);
    }
}
