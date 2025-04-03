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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->uuid('user1_uid')->index();
            $table->uuid('user2_uid')->index();
            $table->uuid('event_uid')->index();
            $table->timestamps();
            $table->foreign('user1_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('user2_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('event_uid')->references('uid')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
