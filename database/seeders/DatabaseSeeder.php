<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\TimeZone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::disk('r2')->deleteDirectory('hooky/profile');

        Company::create([
            'uid' => '54ce8856-fb28-4ff9-bae5-6ed039829959',
            'name' => 'Empresa de Prueba',
            'email' => 'demo@hookyapp.es',
            'password' => 'Demo2025',
            'timezone_uid' => TimeZone::find(2)->uid,
            'pricing_plan_uid' => \App\Models\PricingPlan::find(4)->uid,
        ]);

        // $this->call(DevSeeder::class);
    }
}
