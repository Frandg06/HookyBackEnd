<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Role;
use App\Enums\User\GenderEnum;
use Illuminate\Support\Facades\Hash;
use App\Enums\User\SexualOrientationEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uid' => fake()->uuid(),
            'name' => fake()->name(),
            'surnames' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('a'),
            'gender' => fake()->randomElement(GenderEnum::cases())->value,
            'sexual_orientation' => fake()->randomElement(SexualOrientationEnum::cases())->value,
            'role_id' => Role::USER,
            'born_date' => fake()->date(),
            'description' => fake()->paragraph(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
