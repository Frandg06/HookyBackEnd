<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\CreateEventRequest;
use App\Http\Services\EventService;

class EventController extends Controller
{
    private EventService $eventService;
    
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function store(CreateEventRequest $request)
    {
        try {            
            $validated = $request->only('st_date', 'end_date', 'timezone', 'likes', 'superlikes');

            $event = $this->eventService->store($validated);
    
            return response()->json(['success' => true, 'resp' => $event], 200);

        } catch (ApiException $e) {
            return $e->render();
        } catch (\Throwable $e) { 
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }
}
