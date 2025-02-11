<?php
namespace App\Http\Services;

use App\Exceptions\ApiException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventService
{

  public function store(Array $data)
  {
    DB::beginTransaction();
    try {
      $company = request()->user();
      $nowInstance = Carbon::now()->setTimezone($data['timezone']);
      $now = Carbon::parse($nowInstance);
      $st_date = Carbon::parse($data['st_date']);
      $end_date = Carbon::parse($data['end_date']);
      $diff = $st_date->diffInHours($end_date);
      
      Log::info("EventController->store", ['company' => $company]);
      if($company->checkEventInSameDay($st_date)) throw new ApiException("event_same_day", 409);
      if(!$company->checkEventLimit()) throw new ApiException("event_limit_reached", 409);
      
      if($st_date < $now) throw new ApiException("start_date_past", 409);
      if($diff < 0) return throw new ApiException("end_date_before_start", 409);
      if($diff > 12) return throw new ApiException("event_duration_exceeded", 409);
      if($diff < 2) return throw new ApiException("event_duration_too_short", 409);

      $st_date_parse = Carbon::parse($data['st_date']); 
      $end_date_parse = $end_date;

      $company->events()->create([
        'st_date' => $st_date_parse->format('Y-m-d H:i'),
        'end_date' => $end_date_parse->format('Y-m-d H:i'),
        'timezone' => $data['timezone'],
        'likes' => $data['likes'],
        'super_likes' => $data['superlikes'],
      ]);

      DB::commit();

      $last_event = $company->events()->firstNextEvent()->first();

      return $last_event;
    } catch (ApiException $e) {
      DB::rollBack();
      throw new ApiException($e->getMessage(), $e->getCode());
    } catch (\Exception $e) {
      DB::rollBack();
      Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
      throw new ApiException("create_event_ko", 500);
    }
  }
  
}