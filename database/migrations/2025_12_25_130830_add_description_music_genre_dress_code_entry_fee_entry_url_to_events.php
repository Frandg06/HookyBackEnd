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
        Schema::table('events', function (Blueprint $table) {
            $table->text('description')->nullable()->after('banner_image');
            $table->string('music_genre')->nullable()->after('description');
            $table->string('dress_code')->nullable()->after('music_genre');
            $table->string('entry_fee', 8)->nullable()->after('dress_code');
            $table->string('entry_url')->nullable()->after('entry_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['description', 'music_genre', 'dress_code', 'entry_fee', 'entry_url']);
        });
    }
};
