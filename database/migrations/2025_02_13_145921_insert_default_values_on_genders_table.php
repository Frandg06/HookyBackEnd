<?php

declare(strict_types=1);

use App\Models\Gender;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $arr = [
            ['id' => 1, 'name' => 'Female'],
            ['id' => 2, 'name' => 'Male'],
        ];

        foreach ($arr as $item) {
            Gender::create($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Gender::all()->each(function ($item) {
            $item->delete();
        });
    }
};
