<?php

declare(strict_types=1);

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
final class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Event::class;

    public function definition(): array
    {

        $colors = [
            'label-sky',
            'label-emerald',
            'label-orange',
            'label-indigo',
            'label-red',
            'label-purple',
            'label-yellow',
        ];

        $st_date = fake()->dateTimeInInterval('-1 months', '-10 days');
        $end_date = Carbon::parse($st_date)->addHours(8)->toDateString();

        return [
            'uid' => fake()->uuid(),
            'name' => fake()->name(),
            'company_uid' => '54ce8856-fb28-4ff9-bae5-6ed039829959',
            'st_date' => $st_date,
            'end_date' => $end_date,
            'timezone' => 'Europe/Madrid',
            'likes' => fake()->numberBetween(1, 100),
            'super_likes' => fake()->numberBetween(1, 100),
            'colors' => $colors[rand(0, count($colors) - 1)],
        ];
    }
}
