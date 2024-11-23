<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private EventService $eventService;
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'st_date' => 'required|date',
                'en_date' => 'nullable|date',
            ]);
            $company = $request->user();
    
            $event = $this->eventService->store($company, $request);
    
            return response()->json([
                'status' => 'success',
                'resp' => $event,
            ],200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }
}
