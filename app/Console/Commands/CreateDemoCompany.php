<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\PricingPlan;
use App\Models\TimeZone;
use Illuminate\Console\Command;

class CreateDemoCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:demo-company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Company::create([
            'uid' => '54ce8856-fb28-4ff9-bae5-6ed039829959',
            'name' => 'Empresa de Prueba',
            'email' => 'demo@hookyapp.es',
            'password' => 'Demo2025',
            'timezone_uid' => TimeZone::find(2)->uid,
            'pricing_plan_uid' => PricingPlan::find(4)->uid,
        ]);
    }
}
