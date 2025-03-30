<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\TimeZone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Storage::disk('r2')->deleteDirectory('hooky/profile');
        Storage::disk('r2')->deleteDirectory('hooky/qr');

        $company = Company::create([
            'uid' => '3333dbce-508d-4c64-a8aa-ede97081576f',
            'name' => 'Studio54',
            'email' => 'test@test.es',
            'password' => 'a',
            'timezone_uid' => TimeZone::find(2)->uid,
            'pricing_plan_uid' => \App\Models\PricingPlan::find(1)->uid
        ]);

        $company = Company::create([
            'uid' => '54ce8856-fb28-4ff9-bae5-6ed039829959',
            'name' => 'Scrapworld',
            'email' => 'fdiez86@gmail.com',
            'password' => '234Karatedo',
            'timezone_uid' => TimeZone::find(2)->uid,
            'pricing_plan_uid' => \App\Models\PricingPlan::find(4)->uid
        ]);


        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $company->link);

        Storage::disk('r2')->put('hooky/qr/' . $company->uid . '.png', $response->body());


        $this->call(DevSeeder::class);
    }
}
