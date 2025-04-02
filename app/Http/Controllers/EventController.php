<?php

namespace App\Http\Controllers;

use App\Http\Filters\EventFilter;
use App\Http\Orders\EventOrdenator;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Services\EventService;
use Illuminate\Http\JsonResponse;
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
        $validated = $request->safe()->only('st_date', 'end_date', 'timezone', 'likes', 'super_likes', 'name', 'colors', 'st_hour', 'end_hour');
        $response = $this->eventService->store($validated);
        return $this->response($response);
    }

    public function getCalendarEvents(Request $request): JsonResponse
    {
        $request->validate(['dates' => 'required']);
        $dates = explode(',', $request->dates);
        $response = $this->eventService->getCalendarEvents($dates);
        return $this->response($response);
    }

    public function getEvents(EventFilter $filter, EventOrdenator $order, Request $request): JsonResponse
    {
        $response = $this->eventService->getEvents($filter, $order, $request->limit);
        return $this->response($response);
    }

    public function updateEvent(UpdateEventRequest $request, $uuid)
    {
        $validated = $request->safe()->only('st_date', 'end_date', 'timezone', 'likes', 'super_likes', 'name', 'colors', 'st_hour', 'end_hour');
        $response = $this->eventService->update($validated, $uuid);
        return $this->response($response);
    }

    public function getEventsByUuid($uuid): JsonResponse
    {
        $response =  $this->eventService->show($uuid);
        return $this->response($response);
    }

    public function deleteEventById($uuid): JsonResponse
    {
        $this->eventService->delete($uuid);
        return $this->response(true);
    }

    public function getExportEvents(EventFilter $filter, EventOrdenator $order, Request $request): JsonResponse
    {
        $response = $this->eventService->getExportEvents($filter, $order, $request->limit);
        return $this->response($response);
    }

    public function getEventsFillable(EventFilter $filter): JsonResponse
    {
        $response = $this->eventService->getEventsFillable($filter);
        return $this->response($response);
    }

    public function getTicketDispatcher(Request $request): JsonResponse
    {
        $event = $request->event;
        $response = $this->eventService->getTicketDispatcher($event);
        return $this->response($response);
    }
}
