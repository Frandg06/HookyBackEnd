<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventsMockSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $company = Company::orderBy('id', 'desc')->first();
        $tz = $company->timezone->name;

        $events = [
            [
                'st_date' => now($tz)->format('Y-m-d H:i'),
                'end_date' => now($tz)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 0',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
            [
                'st_date' => now($tz)->subMonths(1)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subMonths(1)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 1',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
            [
                'st_date' => now($tz)->subMonths(2)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subMonths(2)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 2',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
            [
                'st_date' => now($tz)->subMonths(3)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subMonths(3)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 3',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
            [
                'st_date' => now($tz)->subMonths(4)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subMonths(4)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 4',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
            [
                'st_date' => now($tz)->subMonths(5)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subMonths(5)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 5',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
            [
                'st_date' => now($tz)->subMonths(6)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subMonths(6)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 6',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
            [
                'st_date' => now($tz)->subMonths(7)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subMonths(7)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 7',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
        ];

        foreach ($events as $event) {
            \App\Models\Event::create($event);
        }
    }
}
