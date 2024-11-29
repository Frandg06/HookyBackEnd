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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->uuid('company_uid');
            $table->string('code');
            $table->boolean('redeemed')->default(false);
            $table->dateTime('redeemed_at')->nullable();
            $table->integer('likes')->default(5);
            $table->integer('super_likes')->default(1);
            $table->timestamps();
            $table->foreign('company_uid')->references('uid')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
