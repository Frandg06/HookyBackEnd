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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->uuid('chat_uid')->index();
            $table->uuid('sender_uid')->index();
            $table->text('message');
            $table->boolean('read_at')->default(false);
            $table->timestamps();
            $table->foreign('chat_uid')->references('uid')->on('chats')->onDelete('cascade');
            $table->foreign('sender_uid')->references('uid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
