<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventsMockSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $company = Company::find(1);

        $events = [
            [
                'st_date' => now()->format('Y-m-d H:i'),
                'end_date' => now()->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 0',
                'company_uid' => $company->uid,
            ],
            [
                'st_date' => now()->subMonths(1)->format('Y-m-d H:i'),
                'end_date' => now()->subMonths(1)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 1',
                'company_uid' => $company->uid,
            ],
            [
                'st_date' => now()->subMonths(2)->format('Y-m-d H:i'),
                'end_date' => now()->subMonths(2)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 2',
                'company_uid' => $company->uid,
            ],
            [
                'st_date' => now()->subMonths(3)->format('Y-m-d H:i'),
                'end_date' => now()->subMonths(3)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 3',
                'company_uid' => $company->uid,
            ],
            [
                'st_date' => now()->subMonths(4)->format('Y-m-d H:i'),
                'end_date' => now()->subMonths(4)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 4',
                'company_uid' => $company->uid,
            ],
            [
                'st_date' => now()->subMonths(5)->format('Y-m-d H:i'),
                'end_date' => now()->subMonths(5)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 5',
                'company_uid' => $company->uid,
            ],
            [
                'st_date' => now()->subMonths(6)->format('Y-m-d H:i'),
                'end_date' => now()->subMonths(6)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 6',
                'company_uid' => $company->uid,
            ],
            [
                'st_date' => now()->subMonths(7)->format('Y-m-d H:i'),
                'end_date' => now()->subMonths(7)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Mock Event 7',
                'company_uid' => $company->uid,
            ],
        ];

        foreach ($events as $event) {
            \App\Models\Event::create($event);
        }
        
    }
}
