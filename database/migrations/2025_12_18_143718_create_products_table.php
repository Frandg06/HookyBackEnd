<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->unique()->index();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('price_id')->unique();
            $table->integer('limit_users');
            $table->integer('limit_events');
            $table->integer('ticket_limit');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'ProductSeeder',
            '--force' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
