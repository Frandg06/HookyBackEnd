<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Filters\TicketFilter;
use App\Http\Orders\TicketOrdenator;
use App\Http\Resources\Exports\TicketExportResource;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketService extends Service
{
    public function getTickets(TicketFilter $filter, TicketOrdenator $order)
    {
        try {
            $tickets = $this->company()->tickets()->filter($filter)->sort($order)->paginate(10);
            $data = TicketResource::collection($tickets);
            return [
                "data" => $data,
                "current_page" => $tickets->currentPage(),
                "from" => $tickets->firstItem(),
                "last_page" => $tickets->lastPage(),
                "path" => $tickets->path(),
                "per_page" => $tickets->perPage(),
                "to" => $tickets->lastItem(),
                "total" => $tickets->total()
            ];
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('get_tickets_ko', 500);
        }
    }

    public function generateTickets(array $data, string $uuid)
    {
        DB::beginTransaction();
        try {

            $event = Event::find($uuid);

            if (Carbon::parse($event->end_date)->isPast()) {
                return $this->responseError('event_is_past', 400);
            }

            if ($event->tickets->count() > $event->company->pricing_plan->ticket_limit) {
                return $this->responseError('tickets_limit_exceeded', 400);
            }

            Ticket::factory()->count($data['count'])->create([
                'company_uid' => $this->company()->uid,
                'event_uid' => $uuid,
                'redeemed' => false,
                'likes' => $data['likes'],
                'super_likes' => $data['superlikes'],
                'name' => $data['name'],
                'price' => $data['price'],
                'created_at' => now(),
                'updated_at' => now(),
                'user_uid' => null,
                'redeemed_at' => null
            ]);

            DB::commit();

            return  $this->company()->tickets()->paginate(10);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('generate_tickets_ko', 500);
        }
    }

    public function redeem(string $code)
    {
        DB::beginTransaction();
        try {

            $company_uid = $this->user()->company_uid;
            $event_uid = $this->user()->event_uid;

            $ticket = Ticket::getTicketByCompanyEventAndCode($company_uid, $code)->first();

            if (!$ticket) {
                throw new ApiException('ticket_invalid', 400);
            }


            $ticket->ticketsRedeem()->create([
                'user_uid' => $this->user()->uid,
                'event_uid' => $event_uid,
                'company_uid' => $company_uid,
            ]);

            $tz = $this->user()->events()->activeEventData()->event->timezone;

            $ticket->update([
                'redeemed' => true,
                'redeemed_at' => now($tz)
            ]);

            $this->user()->events()->update([
                'likes' => $this->user()->like_credits + $ticket->likes,
                'super_likes' => $this->user()->super_like_credits + $ticket->super_likes
            ]);

            DB::commit();

            return [
                'user_total' => [
                    'super_like_credits' => $this->user()->super_like_credits,
                    'like_credits' => $this->user()->like_credits,
                ],
                'ticket_add' => [
                    'super_likes' => $ticket->super_likes,
                    'likes' => $ticket->likes,
                ]
            ];
        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en ' . __CLASS__ . '->' . __FUNCTION__, ['exception' => $e]);
            throw new ApiException('generate_tickets_ko', 500);
        }
    }

    public function getTicketsToExport(TicketFilter $filter, TicketOrdenator $order)
    {
        try {
            $tickets = $this->company()->tickets()->filter($filter)->sort($order)->get();
            return TicketExportResource::collection($tickets);
        } catch (\Exception $e) {
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('get_tickets_ko', 500);
        }
    }
}
