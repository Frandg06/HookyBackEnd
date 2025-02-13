<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $arr = [
            ["id" => 1, "name" => "Bisexual" ],
            ["id" => 2, "name" => "Heterosexual" ],
            ["id" => 3, "name" => "Homosexual" ],
        ];

        DB::table('sexual_orientations')->insert($arr);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('sexual_orientations')->get()->each(function ($item) {
            DB::table('sexual_orientations')->where('id', $item->id)->delete();
        });
    }
};
