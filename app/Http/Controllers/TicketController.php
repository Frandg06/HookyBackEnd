<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use App\Http\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        try {

            $tickets = $request->user()->tickets()->paginate(10);

            return response()->json(["resp" => $tickets, "success" => true], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => true, "message" => __('i18n.unexpected_error')], 500);
        }
    }

    public function generateTickets(CreateTicketRequest $request)
    {
        $data = $request->only(['count', 'likes', 'superlikes', 'event_uid']);

        $tickets = $this->ticketService->generateTickets($data);

        return response()->json(["resp" => $tickets, "success" => true], 200);
    }

    public function redeem(Request $request)
    {
        $code = $request->code;
        $ticket = $this->ticketService->redeem($code);

        return response()->json(["resp" => $ticket, "success" => true], 200);
    }
}
