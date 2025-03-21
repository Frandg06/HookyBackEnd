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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique()->index();
            $table->uuid('company_uid')->index();
            $table->uuid('event_uid')->nullable()->index();
            $table->uuid('user_uid')->nullable()->index();
            $table->string('code');
            $table->boolean('redeemed')->default(false);
            $table->dateTime('redeemed_at')->nullable();
            $table->integer('likes')->default(5);
            $table->integer('super_likes')->default(1);
            $table->integer('price')->default(0);
            $table->timestamps();
            $table->foreign('company_uid')->references('uid')->on('companies')->onDelete('cascade');
            $table->foreign('event_uid')->references('uid')->on('events')->onDelete('cascade');
            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
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
