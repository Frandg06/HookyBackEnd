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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->string('name')->nullable()->default('No name');
            $table->uuid('company_uid');
            $table->dateTime('st_date');
            $table->dateTime('end_date');
            $table->string('timezone');
            $table->integer('likes');
            $table->integer('super_likes');
            $table->foreign('company_uid')->references('uid')->on('companies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
