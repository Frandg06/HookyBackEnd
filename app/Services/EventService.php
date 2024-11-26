<?php
namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\UsersInteraction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventService
{

  public function store(Company $company, Request $request) 
  {
    try {
      
      $nowInstance = Carbon::now()->setTimezone($request->timezone);
      $now = Carbon::parse($nowInstance);
      $st_date = Carbon::parse($request->st_date);

      if($st_date < $now) {
        throw new \Exception("La fecha de inicio debe ser mayor a la actual");
      }


      ($request->st_date === $request->end_date) 
        ? $end_date = null
        : $end_date = Carbon::parse($request->end_date);
      

      $st_date_parse = Carbon::parse($request->st_date); 
      $end_date_parse = $end_date ?? (clone $st_date)->addHours(8);

      $event = $company->events()->create([
        'st_date' => $st_date_parse->format('Y-m-d H:i'),
        'end_date' => $end_date_parse->format('Y-m-d H:i'),
        'timezone' => $request->timezone,
        'likes' => $request->likes,
        'super_likes' => $request->superlikes,
      ]);

      $last_event = $company->events()->where('st_date', '>', Carbon::now())->orderBy('st_date', 'asc')->first();

      return $last_event;

    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }
  
}