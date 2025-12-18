<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;

final class OrderRepository
{
    public function createOrder(array $data)
    {
        Order::create($data);
    }
}
