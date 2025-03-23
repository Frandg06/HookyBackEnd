<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            \App\Models\NotificationsType::create([
                'name' => $type,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\NotificationsType::all()->each(function ($item) {
            $item->delete();
        });
    }
};
