<?php

namespace App\Http\Controllers;

use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Http\Requests\CreateEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Services\EventService;
use Carbon\Carbon;
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
        $validated = $request->only('st_date', 'end_date', 'timezone', 'likes', 'super_likes', 'name', 'colors');
        $event = $this->eventService->store($validated);

        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function getCalendarEvents(Request $request): JsonResponse
    {
        if (!$request->has('dates')) {
            return response()->json(['error' => true, 'message' => 'dates_not_provided'], 400);
        }

        $dates = explode(',', $request->dates);
        $events = $this->eventService->getCalendarEvents($dates);
        return response()->json(['success' => true, 'resp' => $events], 200);
    }

    public function getEvents(EventFilter $filter, EventOrdenator $order, Request $request): JsonResponse
    {
        $events = $this->eventService->getEvents($filter, $order, $request->limit);
        return response()->json(['success' => true, 'resp' => $events], 200);
    }

    public function updateEvent(Request $request, $uuid)
    {
        $validated = $request->only('st_date', 'end_date', 'timezone', 'likes', 'super_likes', 'name', 'colors');
        $event = $this->eventService->update($validated, $uuid);
        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function getEventsByUuid($uuid): JsonResponse
    {
        $event =  $this->eventService->show($uuid);
        return response()->json(['success' => true, 'resp' => $event], 200);
    }

    public function deleteEventById($uuid): JsonResponse
    {
        $this->eventService->delete($uuid);
        return response()->json(['success' => true, 'message' => __('i18n.event_deleted')], 200);
    }
}
