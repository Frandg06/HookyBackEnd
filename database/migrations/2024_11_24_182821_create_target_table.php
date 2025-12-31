<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('target_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uid')->index();
            $table->uuid('target_user_uid')->index();
            $table->string('interaction')->index();
            $table->uuid('event_uid')->index();
            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('target_user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('event_uid')->references('uid')->on('events')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('target_users');
    }
};
