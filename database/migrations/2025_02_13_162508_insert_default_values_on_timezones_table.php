<?php

use App\Models\TimeZone;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $timezones = [
            ['name' => 'Europe/London', 'utc_offset' => '0'],
            ['name' => 'Europe/Berlin', 'utc_offset' => '1'],
            ['name' => 'Europe/Athens', 'utc_offset' => '2'],
            ['name' => 'Europe/Moscow', 'utc_offset' => '3'],
            ['name' => 'Europe/Samara', 'utc_offset' => '4'],
            ['name' => 'America/New_York', 'utc_offset' => '-5'],
            ['name' => 'America/Chicago', 'utc_offset' => '-6'],
            ['name' => 'America/Denver', 'utc_offset' => '-7'],
            ['name' => 'America/Los_Angeles', 'utc_offset' => '-8'],
            ['name' => 'America/Anchorage', 'utc_offset' => '-9'],
            ['name' => 'Pacific/Honolulu', 'utc_offset' => '-10'],
        ];

        foreach ($timezones as $timezone) {
            TimeZone::create($timezone);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        TimeZone::all()->each(function ($item) {
            $item->delete();
        });
    }
};
