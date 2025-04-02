<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Http\Filters\TicketFilter;
use App\Http\Orders\TicketOrdenator;
use App\Http\Resources\EventResource;
use App\Http\Resources\Exports\TicketExportResource;
use App\Http\Resources\TicketResource;
use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService extends Service
{
    public function getTickets(TicketFilter $filter, TicketOrdenator $order, int $limit)
    {
        try {
            $tickets = $this->company()->tickets()->filter($filter)->sort($order)->paginate($limit);
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

            if ($event->tickets->count() > $event->company->pricingPlan->ticket_limit) {
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

            return EventResource::make($event->refresh());
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

            $ticket = Ticket::where('code', $code)
                ->where('event_uid', $event_uid)
                ->where('redeemed', false)
                ->first();

            if (!$ticket) {
                throw new ApiException('ticket_invalid', 400);
            }

            $tz = $this->user()->events()->activeEventData()->event->timezone;

            $ticket->update([
                'user_uid' => $this->user()->uid,
                'redeemed' => true,
                'redeemed_at' => now($tz)
            ]);


            $this->user()->events()->update([
                'likes' => $this->user()->event->likes + $ticket->likes,
                'super_likes' => $this->user()->event->superlikes + $ticket->super_likes
            ]);

            DB::commit();

            return [
                'user_total' => [
                    'super_like_credits' => $this->user()->event->super_likes,
                    'like_credits' => $this->user()->event->likes,
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

    public function getQrCode(Event $event, string $type)
    {
        DB::beginTransaction();
        try {
            $ticket = Ticket::where('event_uid', $event->uid)
                ->where('redeemed', false)
                ->where("generated", false)
                ->whereRaw('LOWER(name) = ?', [strtolower($type)])
                ->inRandomOrder()
                ->first();

            if (!$ticket) {
                return $this->responseError('ticket_not_found', 404);
            }

            $url = config('app.front_url') . '/redeem?code=' . $ticket->code;

            $qrCode = QrCode::size(300)->generate($url);

            $ticket->update(['generated' => true]);

            DB::commit();

            return $qrCode;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError($e, __CLASS__, __FUNCTION__);
            return $this->responseError('get_qr_code_ko', 500);
        }
    }
}
