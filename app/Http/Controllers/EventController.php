<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Filters\EventFilter;
use App\Http\Requests\CreateEventRequest;
use App\Http\Services\EventService;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    private EventService $eventService;
    
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;    
    }

    public function store(CreateEventRequest $request)
    {  
        $validated = $request->only('st_date', 'end_date', 'timezone', 'likes', 'super_likes', 'name', 'colors');
        $event = $this->eventService->store($validated);
    
        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function getCalendarEvents(Request $request): JsonResponse
    {
        if(!$request->has('dates')) {
            return response()->json(['error' => true, 'message' => 'dates_not_provided'], 400);
        }

        $dates = explode(',', $request->dates);
        $events = $this->eventService->getCalendarEvents($dates);
        return response()->json(['success' => true, 'resp' => $events], 200);
    }

    public function getEvents(EventFilter $filter)
    {
        $company = request()->user();

        $events =   $company->events()
                    ->filter($filter)
                    ->paginate(10);

        return response()->json(['success' => true, 'resp' => $events], 200);
    }

    public function updateEvent(Request $request, $uuid)
    {
        $company = request()->user();
        $validated = $request->only('st_date', 'end_date', 'timezone', 'likes', 'super_likes', 'name', 'colors');
        Log::info($request->all());
        $event =   $company->events()->where('uid', $uuid)->update($validated);

        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function getEventsByUuid($uuid) : JsonResponse{
        $company = request()->user();
        $event =   $company->events()->where('uid', $uuid)->first();
        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function deleteEventById($uuid) : JsonResponse
    {
        $company = request()->user();
        $event = $company->events()->where('uid', $uuid)->first();

        if(!$event) {
            return response()->json(['error' => true, 'message' => __('i18n.event_not_found')], 404);
        }
        
        $st_date = Carbon::parse($event->st_date);

        if($st_date->isPast()) {
            return response()->json(['error' => true, 'message' => __('i18n.event_cant_delete')], 409);
        }

        $event->delete();

        return response()->json(['success' => true, 'message' => __('i18n.event_deleted')], 200);
    }
}
