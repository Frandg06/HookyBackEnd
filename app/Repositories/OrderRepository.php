<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;

final class OrderRepository
{
    public function createOrder(array $data): Order
    {
        return Order::create($data);
    }

    public function firstOrCreateOrder(array $data): Order
    {
        return Order::firstOrCreate([
            'session_id' => $data['session_id'],
        ], $data);
    }
}
