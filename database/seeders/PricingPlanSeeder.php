<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elements = [
            ['name' => 'Basic', 'price' => 149.90, 'limit_users' => 2000, 'limit_events' => 2, 'ticket_limit' => 1000],
            ['name' => 'Pro', 'price' => 249.90, 'limit_users' => 4000, 'limit_events' => 5, 'ticket_limit' => 3000],
            ['name' => 'Premium', 'price' => 349.90, 'limit_users' => 8000, 'limit_events' => 10, 'ticket_limit' => 6000],
            ['name' => 'Ultimate', 'price' => 449.90, 'limit_users' => 10000000000, 'limit_events' => 10000000000, 'ticket_limit' => 10000000000],
        ];

        foreach ($elements as $element) {
            \App\Models\PricingPlan::create($element);
        }
    }
}
