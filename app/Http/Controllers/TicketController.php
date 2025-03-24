<?php

namespace App\Http\Controllers;

use App\Http\Filters\TicketFilter;
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

    public function getTickets(TicketFilter $filter)
    {
        try {

            $tickets = $this->ticketService->getTickets($filter);
            return response()->json(['resp' => $tickets, 'success' => true], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => __('i18n.unexpected_error')], 500);
        }
    }

    public function generateTickets(CreateTicketRequest $request, string $uuid)
    {
        $data = $request->safe()->only(['count', 'likes', 'superlikes', 'name', 'price']);
        $tickets = $this->ticketService->generateTickets($data, $uuid);
        return $this->response($tickets);
    }

    public function redeem(Request $request)
    {
        $code = $request->code;
        $ticket = $this->ticketService->redeem($code);

        return response()->json(['resp' => $ticket, 'success' => true], 200);
    }
}
