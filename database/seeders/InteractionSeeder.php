<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Interaction;
use Illuminate\Database\Seeder;

final class InteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interactions = [
            'super_like',
            'like',
            'dislike',
        ];

        foreach ($interactions as $interaction) {
            Interaction::create([
                'name' => $interaction,
            ]);
        }
    }
}
