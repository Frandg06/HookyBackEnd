<?php

use App\Models\SexualOrientation;
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
        
        foreach ($arr as $item) {
            SexualOrientation::create($item);
        }

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
