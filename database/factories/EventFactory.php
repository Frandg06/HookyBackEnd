<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
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
            '#38bdf8,#0369a1',
            '#6ee7b7,#059669',
            '#fb923c,#ea580c',
            '#a5b4fc,#4f46e5',
            '#fca5a5,#dc2626',
            '#d8b4fe,#9333ea',
            '#fde047,#ca8a04',
        ];


        return [
            'uid' => fake()->uuid(),
            'name' => fake()->name(),
            'company_uid' => '1d59e992-7865-41c5-ad7d-d271ccf4e7fc',
            'st_date' => fake()->dateTimeInInterval('-1 year', '+1 year'), 
            'end_date' => fake()->dateTimeInInterval('-1 year', '+1 year'),
            'timezone' => 'Europe/Madrid',
            'likes' => fake()->numberBetween(1, 100),
            'super_likes' => fake()->numberBetween(1, 100),
            'colors' => $colors[rand(0, count($colors) - 1)]
        ];
    }
}
