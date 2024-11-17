<?php

namespace Database\Seeders;

use App\Models\Interaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interactions = [
            "super_like",
            "like",
            "dislike",
        ];

        foreach ($interactions as $interaction) {
            Interaction::create([
                'name' => $interaction,
            ]);
        }
    }
}
