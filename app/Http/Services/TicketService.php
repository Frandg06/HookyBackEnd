<?php

namespace App\Http\Services;

use App\Exceptions\ApiException;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketService
{
    public function generateTickets($data) 
    {
        DB::beginTransaction();
        try {
            
            $company = request()->user();
            if (!is_numeric($data['count'])) throw new ApiException("tickets_not_numeric", 400);
            if ($data['count'] < 1) throw new ApiException("tickets_minimum", 400);
            if ($data['count'] > 1000) throw new ApiException("tickets_maximum", 400);


            $tickets = [];  

            while (count($tickets) < $data['count']) {
                $code = strtoupper(Str::random(6));
                $tickets[] = [
                    'uid' => (string) Str::uuid(), 
                    'company_uid' => $company->uid,
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

            return [
                "all_tickets" => $company->tickets()->paginate(10),
                "company_tickets" => $company->tickets()->limit(5)->orderBy('redeemed_at', 'desc')->get()
            ];

        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
            throw new ApiException("generate_tickets_ko", 500);
        }
    }

    public function redeem($code) 
    {
        DB::beginTransaction();
        try {
            $user = request()->user();

            $company_uid = $user->company_uid;
            $event_uid = $user->event_uid;

            $ticket = Ticket::getTicketByCompanyEventAndCode($company_uid, $code)->first();

            if (!$ticket) throw new ApiException("ticket_invalid", 400);


            $ticket->ticketsRedeem()->create([
                'user_uid' => $user->uid,
                'event_uid' => $event_uid,
                'company_uid' => $company_uid,
            ]);

            $tz = $user->events()->activeEventData()->event->timezone;
            
            $ticket->update([
               "redeemed" => true,
               "redeemed_at" => now($tz)
            ]);

            $user->events()->update([
                'likes' => $user->like_credits + $ticket->likes,
                'super_likes' => $user->super_like_credits + $ticket->super_likes
            ]);

            DB::commit();

            return [
                "user_total" => [
                    "super_like_credits" => $user->super_like_credits,
                    "like_credits" => $user->like_credits,
                ],
                "ticket_add" => [
                    "super_likes" => $ticket->super_likes,
                    "likes" => $ticket->likes,
                ]
            ];

        } catch (ApiException $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en " . __CLASS__ . "->" . __FUNCTION__, ['exception' => $e]);
            throw new ApiException("generate_tickets_ko", 500);
        }
    }
}