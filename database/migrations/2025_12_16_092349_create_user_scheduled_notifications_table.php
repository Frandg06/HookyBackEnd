<?php

declare(strict_types=1);

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
        Schema::create('user_scheduled_notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uid');
            $table->uuid('event_uid');
            $table->dateTime('scheduled_at');
            $table->enum('status', ['pending', 'sent'])->default('pending');
            $table->timestamps();

            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('event_uid')->references('uid')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_scheduled_notifications');
    }
};
