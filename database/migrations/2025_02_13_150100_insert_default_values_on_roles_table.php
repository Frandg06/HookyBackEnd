<?php

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

        DB::table('roles')->insert($arr);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->get()->each(function ($item) {
            DB::table('roles')->where('id', $item->id)->delete();
        });
    }
};
