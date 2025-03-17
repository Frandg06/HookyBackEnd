<?php

namespace App\Http\Controllers;

use App\Http\Filters\EventFilter;
use App\Http\Requests\CreateEventRequest;
use App\Http\Services\EventService;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
        $validated = $request->only('st_date', 'end_date', 'timezone', 'likes', 'super_likes', 'name', 'colors', 'emoji');
        $event = $this->eventService->store($validated);
    
        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function getCalendarEvents(EventFilter $filter)
    {
        $company = request()->user();

        $events =   $company->events()
                    ->filter($filter)
                    ->get(['name', 'st_date as start', 'end_date as end', 'colors', 'uid', 'emoji']);

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
        $validated = $request->only('st_date', 'end_date', 'timezone', 'likes', 'super_likes', 'name', 'colors', 'emoji');
        Log::info($request->all());
        $event =   $company->events()->where('uid', $uuid)->update($validated);

        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function getEventsByUuid($uuid) : JsonResponse{
        $company = request()->user();
        $event =   $company->events()->where('uid', $uuid)->first();
        return response()->json(['success' => true, 'resp' => $event], 200);
    }
}
