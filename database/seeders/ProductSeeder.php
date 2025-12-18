<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Enums\ProductEnum;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [];

        if (env('PRODUCTS_ENV') === 'development') {
            $products = [
                'ess' => 'price_1SeDXpFPMp5k4nYMaG8eHN5E',
                'adv' => 'price_1SeDYSFPMp5k4nYMzdYM3P0F',
                'pro' => 'price_1SeDY7FPMp5k4nYMcKnnSPyp',
                'pre' => 'price_1SeDYuFPMp5k4nYMqZ3GQPwx',
                'vip' => 'price_1SeDdtFPMp5k4nYMWVWcWMu1',
            ];
        } else {
            $products = [
                'ess' => 'price_1R9o7RFDb0Tk400S248z0puq',
                'adv' => 'price_1RMBFGFDb0Tk400SRCu8bkdn',
                'pro' => 'price_1RMBEqFDb0Tk400Slj0wZDn0',
                'pre' => 'price_1RMBC9FDb0Tk400SHDxy4Vuz',
                'vip' => 'price_1SfKxHFDb0Tk400SLrhSRqVD',
            ];
        }

        $elements = [
            [
                'name' => ProductEnum::ESSENTIAL,
                'price' => 39.95,
                'price_id' => $products['ess'],
                'limit_users' => 2000,
                'limit_events' => 2,
                'ticket_limit' => 1000,
            ],
            [
                'name' => ProductEnum::ADVANCED,
                'price' => 59.95,
                'price_id' => $products['adv'],
                'limit_users' => 8000,
                'limit_events' => 5,
                'ticket_limit' => 3000,
            ],
            [
                'name' => ProductEnum::PROFESSIONAL,
                'price' => 89.95,
                'price_id' => $products['pro'],
                'limit_users' => 4000,
                'limit_events' => 10,
                'ticket_limit' => 6000,
            ],
            [
                'name' => ProductEnum::PREMIUM,
                'price' => 119.95,
                'price_id' => $products['pre'],
                'limit_users' => -1,
                'limit_events' => -1,
                'ticket_limit' => -1,
            ],
            [
                'name' => ProductEnum::VIP,
                'price' => 14.95,
                'price_id' => $products['vip'],
                'limit_users' => -1,
                'limit_events' => -1,
                'ticket_limit' => -1,
            ],
        ];
        foreach ($elements as $element) {
            Product::create($element);
        }
    }
}
