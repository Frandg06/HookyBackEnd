<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TimeZone;
use Illuminate\Database\Seeder;

final class TimeZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
}
