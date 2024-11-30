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
        Schema::create('users_interactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uid');
            $table->uuid('interaction_user_uid')->nullable();
            $table->unsignedBigInteger('interaction_id')->nullable();
            $table->uuid('event_uid')->nullable();
            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('interaction_user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('interaction_id')->references('id')->on('interactions')->onDelete('cascade');
            $table->foreign('event_uid')->references('uid')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_iteractions');
    }
};
