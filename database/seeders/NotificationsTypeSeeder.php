<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class NotificationsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'like',
            'superlike',
            'hook',
            'message',
        ];

        foreach ($types as $type) {
            \App\Models\NotificationsType::create([
                'name' => $type,
            ]);
        }
    }
}
