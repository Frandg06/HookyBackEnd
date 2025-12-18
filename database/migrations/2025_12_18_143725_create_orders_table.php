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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('uuid')->primary()->unique()->index();
            $table->string('order_number')->unique();
            $table->uuid('user_uid')->nullable()->index();
            $table->uuid('company_uid')->nullable()->index();
            $table->uuid('product_uuid');
            $table->timestamps();

            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->foreign('company_uid')->references('uid')->on('companies')->onDelete('cascade');
            $table->foreign('product_uuid')->references('uuid')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
