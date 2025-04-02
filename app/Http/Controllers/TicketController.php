<?php

namespace App\Http\Controllers;

use App\Http\Filters\TicketFilter;
use App\Http\Orders\TicketOrdenator;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function getTickets(TicketFilter $filter, TicketOrdenator $order, Request $request)
    {
        $tickets = $this->ticketService->getTickets($filter, $order, $request->limit ?? 10);
        return $this->response($tickets);
    }

    public function generateTickets(CreateTicketRequest $request, string $uuid)
    {
        $data = $request->safe()->only(['count', 'likes', 'superlikes', 'name', 'price']);
        $response = $this->ticketService->generateTickets($data, $uuid);
        return $this->response($response);
    }

    public function redeem(Request $request)
    {
        $code = $request->code;
        $ticket = $this->ticketService->redeem($code);

        return response()->json(['resp' => $ticket, 'success' => true], 200);
    }

    public function getTicketsToExport(TicketFilter $filter, TicketOrdenator $order)
    {
        $tickets = $this->ticketService->getTicketsToExport($filter, $order);
        return $this->response($tickets);
    }

    public function getQrCode(Request $request)
    {
        $event = $request->event;
        $type = $request->type;
        $qr = $this->ticketService->getQrCode($event, $type);
        return response($qr)->header('Content-Type', 'image/svg+xml')
            ->header('Access-Control-Allow-Origin', ['https://admin.hookyapp.es', 'https://api.hookyapp.es', 'https://hookybackend-master-dol5s1.laravel.cloud'])
            ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
    }
}
