<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;

final class ProductRepository
{
    public function findProductByPriceId(string $priceId): ?Product
    {
        return Product::where('price_id', $priceId)->first();
    }

    public function findProductByName(string $name): ?Product
    {
        return Product::where('name', $name)->first();
    }
}
