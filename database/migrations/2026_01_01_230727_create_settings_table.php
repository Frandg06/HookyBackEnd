<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uid')->unique();
            $table->boolean('new_like_notification')->default(true);
            $table->boolean('new_superlike_notification')->default(true);
            $table->boolean('new_message_notification')->default(true);
            $table->boolean('event_start_email')->default(true);
            $table->timestamps();
        });

        DB::table('users')->select('id', 'uid')->chunkById(100, function ($users) {
            $settings = [];
            foreach ($users as $user) {
                $settings[] = [
                    'user_uid' => $user->uid,
                    'new_like_notification' => true,
                    'new_superlike_notification' => true,
                    'new_message_notification' => true,
                    'event_start_email' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('settings')->insert($settings);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
