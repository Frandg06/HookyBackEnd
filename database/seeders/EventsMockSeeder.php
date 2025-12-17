<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

final class EventsMockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $company = Company::orderBy('id', 'desc')->first();
        $tz = $company->timezone->name;
        $labels = collect([
            'label-sky',
            'label-emerald',
            'label-orange',
            'label-indigo',
            'label-red',
            'label-purple',
            'label-yellow',
        ]);

        $events = [
            [
                'st_date' => now($tz)->format('Y-m-d H:i'),
                'end_date' => now($tz)->addHour()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Evento de prueba 0',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
                'colors' => $labels->random(),
            ],
            [
                'st_date' => now($tz)->subDays(1)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subDays(1)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Evento de prueba 1',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
                'colors' => $labels->random(),

            ],
            [
                'st_date' => now($tz)->subDays(2)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subDays(2)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Evento de prueba 2',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
                'colors' => $labels->random(),

            ],
            [
                'st_date' => now($tz)->subDays(3)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subDays(3)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Evento de prueba 3',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
                'colors' => $labels->random(),

            ],
            [
                'st_date' => now($tz)->subDays(4)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subDays(4)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Evento de prueba 4',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
                'colors' => $labels->random(),

            ],
            [
                'st_date' => now($tz)->subDays(5)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subDays(5)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Evento de prueba 5',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
                'colors' => $labels->random(),

            ],
            [
                'st_date' => now($tz)->subDays(6)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subDays(6)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Evento de prueba 6',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
            ],
            [
                'st_date' => now($tz)->subDays(7)->format('Y-m-d H:i'),
                'end_date' => now($tz)->subDays(7)->addDay()->format('Y-m-d H:i'),
                'timezone' => 'Europe/Madrid',
                'likes' => 10,
                'super_likes' => 2,
                'name' => 'Evento de prueba 7',
                'company_uid' => $company->uid,
                'code' => Str::uuid(),
                'colors' => $labels->random(),
            ],
        ];

        foreach ($events as $event) {
            \App\Models\Event::create($event);
        }
    }
}
