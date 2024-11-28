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
        Schema::create('ticket_redeems', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('ticket_uid')->nullable();
            $table->string('user_uid')->nullable();
            $table->string('event_uid')->nullable();
            $table->string('redeemed_at')->nullable();
            
            $table->foreign('ticket_uid')->references('uid')->on('tickets')->onDelete('cascade');
            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('event_uid')->references('uid')->on('events')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_redeems');
    }
};
