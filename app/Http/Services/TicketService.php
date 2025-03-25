<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketService extends Service
{
    public function getTickets($filter)
    {
        $tickets = $this->company()->tickets()->paginate(10);

        return  [
            'data' => $tickets->items(),
            'current_page' => $tickets->currentPage(),
            'last_page' => $tickets->lastPage(),
            'total' => $tickets->total(),
            'per_page' => $tickets->perPage(),
        ];
    }

    public function generateTickets(array $data, string $uuid)
    {
        DB::beginTransaction();
        try {

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
}
