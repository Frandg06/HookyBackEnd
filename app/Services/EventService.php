<?php
namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\UsersInteraction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class EventService
{

  public function store(Company $company, Request $request) 
  {
    try {
      $nowInstance = Carbon::now()->setTimezone($request->timezone);
      $now = Carbon::parse($nowInstance);
      $st_date = Carbon::parse($request->st_date);
      $end_date = Carbon::parse($request->end_date);
      $diff = $st_date->diffInHours($end_date);
      

      if($company->checkEventInSameDay($st_date)) throw new CustomException("Ya hay un evento en la fecha elegida, elije otro dia");
      if(!$company->checkEventLimit()) throw new CustomException("No puedes crear mas eventos, has alcanzado el limite");
      
      if($st_date < $now) throw new CustomException("La fecha de inicio debe ser mayor a la actual");
      if($diff < 0) return throw new CustomException("La fecha de fin debe ser mayor a la fecha de inicio");
      if($diff > 12) return throw new CustomException("La duracion maxima de un evento es de 12 horas");
      if($diff < 2) return throw new CustomException("La duracion minima de un evento es de 2 horas");

      
      
      $st_date_parse = Carbon::parse($request->st_date); 
      $end_date_parse = $end_date;

      $company->events()->create([
        'st_date' => $st_date_parse->format('Y-m-d H:i'),
        'end_date' => $end_date_parse->format('Y-m-d H:i'),
        'timezone' => $request->timezone,
        'likes' => $request->likes,
        'super_likes' => $request->superlikes,
      ]);

      $last_event = $company->events()->firstNextEvent()->first();
      return $last_event;

    } catch (Throwable $e) {
      ($e instanceof CustomException)
        ? throw new Exception($e->getMessage())
        : throw new \Exception("Se ha producido un error al crear el evento");
       
    }
  }
  
}