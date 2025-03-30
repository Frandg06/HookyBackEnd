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
            'uid' => '1d59e992-7865-41c5-ad7d-d271ccf4e7fc',
            'name' => 'Studio54',
            'email' => 'test@test.es',
            'password' => 'a',
            'timezone_uid' => TimeZone::find(2)->uid,
            'pricing_plan_uid' => \App\Models\PricingPlan::find(1)->uid
        ]);

        $response = Http::get('https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . $company->link);

        Storage::disk('r2')->put('hooky/qr/' . $company->uid . '.png', $response->body());


        $this->call(DevSeeder::class);
    }
}
