<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use App\Http\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $ticketService;
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request) {
        
        try {
            $tickets = $request->user()->tickets()->paginate(10);
            return response()->json(["resp" => $tickets, "success" => true], 200);

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);

        }
    }

    public function generateTickets(CreateTicketRequest $request) {
        
        try {
            $company = $request->user();
            $data = $request->only(['count', 'likes', 'superlikes']);
            $tickets = $this->ticketService->generateTickets($company, $data);
            return response()->json(["resp" => $tickets, "success" => true], 200);

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);

        }
    }
}
