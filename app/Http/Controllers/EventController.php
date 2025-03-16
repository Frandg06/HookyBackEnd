<?php

namespace App\Http\Controllers;

use App\Http\Filters\EventFilter;
use App\Http\Requests\CreateEventRequest;
use App\Http\Services\EventService;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private EventService $eventService;
    
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;    
    }

    public function store(CreateEventRequest $request)
    {  
        $validated = $request->only('st_date', 'end_date', 'timezone', 'likes', 'superlikes', 'name', 'colors');
        $event = $this->eventService->store($validated);
    
        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function getEvents(Request $request)
    {
        $filter = new EventFilter($request);

        $company = request()->user();

        $events =   $company->events()
                    ->filter($filter)
                    ->get(['name', 'st_date as start', 'end_date as end', 'colors']);

        return response()->json(['success' => true, 'resp' => $events], 200);
    }
}
