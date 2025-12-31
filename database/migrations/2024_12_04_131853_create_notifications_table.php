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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('uid')->primary()->unique()->index();
            $table->uuid('user_uid')->index();
            $table->uuid('event_uid')->index();
            $table->string('type');
            $table->boolean('read_at')->default(false);
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
        Schema::dropIfExists('notifications');
    }
};
