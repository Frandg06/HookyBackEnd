<?php

namespace App\Http\Services;

use App\Exceptions\CustomException;
use App\Models\Company;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketService
{
    public function generateTickets(Company $company, $data) {
      try {

            if (!is_numeric($data['count'])) throw new CustomException("El numero de tickets debe ser un numero");
            if ($data['count'] < 1) throw new CustomException("El numero de tickets debe ser mayor a 1");
            if ($data['count'] > 1000) throw new CustomException("El numero de tickets no puede ser mayor a 1000");

            DB::beginTransaction();
            $tickets = [];  

            while (count($tickets) < $data['count']) {
                $code = strtoupper(Str::random(6));
                $exists = Ticket::where('company_uid', $company->uid)
                                ->where('code', $code)
                                ->where('redeemed', false)
                                ->exists();

                if (!$exists) {
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
            }

            Ticket::insert($tickets);

            DB::commit();
            return [
                "all_tickets" => $company->tickets()->paginate(10),
                "company_tickets" => $company->tickets()->limit(5)->orderBy('redeemed_at', 'desc')->get()
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            ($e instanceof CustomException)
                ? throw new Exception($e->getMessage())
                : throw new Exception("Se ha producido un error al generar tickets");
        }
    }
}