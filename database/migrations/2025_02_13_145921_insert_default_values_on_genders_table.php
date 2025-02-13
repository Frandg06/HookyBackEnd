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
        
        DB::table('genders')->insert([
            'id' => 1,
            'name' => 'Female',
        ]);
        
        DB::table('genders')->insert([
            'id' => 2,
            'name' => 'Male',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('genders')->get()->each(function ($item) {
            DB::table('genders')->where('id', $item->id)->delete();
        });
    }
};
