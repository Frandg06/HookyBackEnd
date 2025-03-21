<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserInterest>
 */
class UserInterestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $intereses = [
            ['name' => 'Cine', 'icon' => 'maki:cinema-11', 'color' => '#10b981', 'bg_color' => '#a7f3d0',],
            ['name' => 'Viajar', 'icon' => 'streamline:travel-airport-earth-airplane-travel-plane-trip-airplane-international-adventure-globe-world', 'color' => '#06b6d4', 'bg_color' => '#a5f3fc',],
            ['name' => 'Lectura', 'icon' => 'grommet-icons:book', 'color' => '#3b82f6', 'bg_color' => '#bfdbfe',],
            ['name' => 'Deporte', 'icon' => 'material-symbols-light:sports-and-outdoors', 'color' => '#ef4444', 'bg_color' => '#fecaca',],
            ['name' => 'Naturaleza', 'icon' => 'pajamas:nature', 'color' => '#eab308', 'bg_color' => '#fef08a',],
            ['name' => 'Música', 'icon' => 'tabler:music', 'color' => '#f97316', 'bg_color' => '#fed7aa',],
            ['name' => 'Cocina', 'icon' => 'icon-park-outline:cook', 'color' => '#8b5cf6', 'bg_color' => '#ddd6fe',],
            ['name' => 'Mascotas', 'icon' => 'streamline:pets-allowed', 'color' => '#ec4899', 'bg_color' => '#fbcfe8',],
            ['name' => 'Arte', 'icon' => 'map:art-gallery', 'color' => '#f43f5e', 'bg_color' => '#fecdd3',],
            ['name' => 'Tecnología', 'icon' => 'ls:pc', 'color' => '#22c55e', 'bg_color' => '#bbf7d0'],
            ['name' => 'Aventura', 'icon' => 'material-symbols:paragliding-outline', 'color' => '#f87171', 'bg_color' => '#fee2e2'],
            ['name' => 'Voluntariado', 'icon' => 'material-symbols:volunteer-activism-outline', 'color' => '#64748b', 'bg_color' => '#cbd5e1',],
            ['name' => 'Fitness', 'icon' => 'mingcute:fitness-line', 'color' => '#d946ef', 'bg_color' => '#f0abfc'],
            ['name' => 'Moda', 'icon' => 'ph:dress-fill', 'color' => '#14b8a6', 'bg_color' => '#99f6e4',],
            ['name' => 'Fotografía', 'icon' => 'tabler:photo', 'color' => '#3a8ef7', 'bg_color' => '#bbf7d0',],
            ['name' => 'Escritura', 'icon' => 'system-uicons:write', 'color' => '#ea580c', 'bg_color' => '#fed7aa'],
            ['name' => 'Café', 'icon' => 'ci:coffe-to-go', 'color' => '#4b5563', 'bg_color' => '#e5e7eb'],
            ['name' => 'Ciencia', 'icon' => 'gridicons:science', 'color' => '#f59e0b', 'bg_color' => '#fef3c7'],
            ['name' => 'Danza', 'icon' => 'mdi:dance-ballroom', 'color' => '#38bdf8', 'bg_color' => '#bae6fd'],
            ['name' => 'Yoga', 'icon' => 'iconoir:yoga', 'color' => '#0d9488', 'bg_color' => '#99f6e4'],
            ['name' => 'Festivales', 'icon' => 'material-symbols:festival', 'color' => '#fca5a5', 'bg_color' => '#fee2e2'],
            ['name' => 'Emprender', 'icon' => 'tdesign:money', 'color' => '#a855f7', 'bg_color' => '#e9d5ff'],
            ['name' => 'Coches', 'icon' => 'bx:car', 'color' => '#6366f1', 'bg_color' => '#e0e7ff',],
            ['name' => 'Teatro', 'icon' => 'maki:theatre', 'color' => '#0ea5e9', 'bg_color' => '#bae6fd',],
            ['name' => 'Acampar', 'icon' => 'material-symbols:camping', 'color' => '#7c3aed', 'bg_color' => '#ddd6fe'],
        ];
        return [
            $intereses[array_rand($intereses)]
        ];
    }
}
