<?php

use App\Models\Role;
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
            ["id" => 1, "name" => "Admin" ],
            ["id" => 2, "name" => "User" ],
            ["id" => 3, "name" => "Vip" ],
        ];

        foreach ($arr as $item) {
            Role::create($item);
        }


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::all()->each(function ($item) {
            $item->delete();
        });
    }
};
