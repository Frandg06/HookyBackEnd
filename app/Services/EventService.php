<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\UsersInteraction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EventService
{

  public function store(Company $company, $request) {

    try {
      $st_date = Carbon::parse($request->st_date); 
      $end_date = $request->end_date ?? (clone $st_date)->addHours(8);

      $event = $company->events()->create([
        'st_date' => $st_date->format('Y-m-d H:i:s'),
        'en_date' => $end_date->format('Y-m-d H:i:s'),
      ]);

      return $event;

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
  
}