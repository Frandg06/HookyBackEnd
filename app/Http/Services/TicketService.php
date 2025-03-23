<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketService extends Service
{
    public function generateTickets(array $data): array
    {
        DB::beginTransaction();
        try {

            $event = $this->company()->events()->where('uid', $data['event_uid'])->first();

            if (!$event) return $this->responseError('event_not_found', 400);

            if (!is_numeric($data['count'])) $this->responseError('tickets_not_numeric', 400);

            if ($data['count'] < 1) $this->responseError('tickets_minimum', 400);

            if ($data['count'] > 1000) $this->responseError('tickets_maximum', 400);

            $tickets = [];

            while (count($tickets) < $data['count']) {
                $code = strtoupper(Str::random(6));
                $tickets[] = [
                    'uid' => (string) Str::uuid(),
                    'company_uid' => $this->company()->uid,
                    'event_uid' => $data['event_uid'],
                    'code' => $code,
                    'redeemed' => false,
                    'likes' => $data['likes'],
                    'super_likes' => $data['superlikes'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Ticket::insert($tickets);

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
