<?php

use App\Models\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $arr = [
            ["id" => 1, "name" => "Female" ],
            ["id" => 2, "name" => "Male" ],
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
