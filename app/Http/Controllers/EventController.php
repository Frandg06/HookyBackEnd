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
                'end_date' => 'nullable|date',
                'timezone' => 'required|string',
                'likes' => 'required|integer',
                'superlikes' => 'required|integer',
            ]);
            
            $company = $request->user();
    
            $event = $this->eventService->store($company, $request);
    
            return response()->json([
                'success' => true,
                'resp' => $event,
            ],200);

        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 400);
        }
    }
}
