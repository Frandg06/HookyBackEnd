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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->uuid('user_uid');
            $table->uuid('emitter_uid')->nullable();
            $table->uuid('event_uid');
            $table->unsignedBigInteger('type_id');
            $table->dateTime('read_at')->nullable();
            $table->timestamps();
            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('emitter_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('event_uid')->references('uid')->on('events')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('notifications_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
