<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
final class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uid' => \Illuminate\Support\Str::uuid(),
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'password' => bcrypt('password'),
            'website' => $this->faker->url,
            'cif' => $this->faker->uuid,
            'pricing_plan_uid' => \App\Models\PricingPlan::inRandomOrder()->first()->uid,
            'timezone_uid' => \App\Models\TimeZone::inRandomOrder()->first()->uid,
        ];
    }
}
