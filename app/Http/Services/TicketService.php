<?php

declare(strict_types=1);

namespace App\Http\Services;

use Throwable;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Exceptions\ApiException;
use App\Http\Filters\TicketFilter;
use Illuminate\Support\Facades\DB;
use App\Http\Orders\TicketOrdenator;
use App\Http\Resources\EventResource;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Exports\TicketExportResource;

final class TicketService extends Service
{
    public function getTickets(TicketFilter $filter, TicketOrdenator $order, int $limit): array
    {

        $tickets = $this->company()->tickets()->filter($filter)->sort($order)->paginate($limit);
        $data = TicketResource::collection($tickets);

        return [
            'data' => $data,
            'current_page' => $tickets->currentPage(),
            'from' => $tickets->firstItem(),
            'last_page' => $tickets->lastPage(),
            'path' => $tickets->path(),
            'per_page' => $tickets->perPage(),
            'to' => $tickets->lastItem(),
            'total' => $tickets->total(),
        ];
    }

    public function generateTickets(array $data, string $uuid): EventResource
    {
        DB::beginTransaction();
        try {
            $event = Event::find($uuid);

            if (Carbon::parse($event->end_date)->isPast()) {
                throw new ApiException('event_expired', 400);
            }

            if ($event->tickets->count() > $event->company->pricingPlan->ticket_limit) {
                throw new ApiException('ticket_limit_reached', 400);
            }

            for ($i = 0; $i < $data['count']; $i++) {
                Ticket::create([
                    'company_uid' => $this->company()->uid,
                    'event_uid' => $uuid,
                    'redeemed' => false,
                    'likes' => $data['likes'],
                    'superlikes' => $data['superlikes'],
                    'name' => $data['name'],
                    'price' => $data['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'user_uid' => null,
                    'redeemed_at' => null,
                    'code' => mb_strtoupper(Str::random(6)),
                ]);
            }

            DB::commit();

            return EventResource::make($event->refresh());
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTicketsToExport(TicketFilter $filter, TicketOrdenator $order): JsonResource
    {
        $tickets = $this->company()->tickets()->filter($filter)->sort($order)->get();

        return TicketExportResource::collection($tickets);
    }

    public function getQrCode(Event $event, string $type): string
    {
        DB::beginTransaction();
        try {
            $ticket = Ticket::where('event_uid', $event->uid)
                ->where('redeemed', false)
                ->where('generated', false)
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($type)])
                ->inRandomOrder()
                ->first();

            if (! $ticket) {
                throw new ApiException('ticket_not_found', 404);
            }

            $url = config('app.front_url').'/redeem?code='.$ticket->code;

            $ticket->update(['generated' => true]);

            DB::commit();

            return $url;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
