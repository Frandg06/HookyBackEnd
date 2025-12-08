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
        $types = [
            'like',
            'superlike',
            'hook',
            'message',
        ];

        foreach ($types as $type) {
            App\Models\NotificationsType::create([
                'name' => $type,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        App\Models\NotificationsType::all()->each(function ($item) {
            $item->delete();
        });
    }
};
