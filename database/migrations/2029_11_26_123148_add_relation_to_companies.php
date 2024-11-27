<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('timezone_uid')->references('uid')->on("time_zones")->onDelete('cascade');
            $table->foreign('pricing_plan_uid')->references('uid')->on("pricing_plans")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['timezone_uid', 'pricing_plan_uid']);
        });
    }
};
