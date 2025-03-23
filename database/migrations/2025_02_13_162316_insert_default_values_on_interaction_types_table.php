<?php

use App\Models\Interaction;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Interaction::all()->each(function ($item) {
            $item->delete();
        });
    }
};
