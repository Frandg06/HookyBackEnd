<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $elements = [
            ['name' => 'Basic', 'price' => 149.90, 'limit_users' => 2000, 'limit_events' => 2],
            ['name' => 'Pro', 'price' => 249.90, 'limit_users' => 4000, 'limit_events' => 5],
            ['name' => 'Premium', 'price' => 349.90, 'limit_users' => 8000, 'limit_events' => 10],
            ['name' => 'Ultimate', 'price' => 449.90, 'limit_users' => 1000000, 'limit_events' => 1000000],
        ];

        foreach ($elements as $element) {
            App\Models\PricingPlan::create($element);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        App\Models\PricingPlan::all()->each(function ($item) {
            $item->delete();
        });
    }
};
