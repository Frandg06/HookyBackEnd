<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
final class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_uid' => $this->faker->uuid,
            'code' => mb_strtoupper(Str::random(6)),
            'redeemed' => false,
            'redeemed_at' => null,
            'superlikes' => $this->faker->numberBetween(0, 100),
            'likes' => $this->faker->numberBetween(0, 100),
            'user_uid' => null,
            'event_uid' => $this->faker->uuid,
            'name' => $this->faker->name,
            'price' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
