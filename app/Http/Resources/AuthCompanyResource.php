<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthCompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $arrayUsersIncome = [
            "labels" => ['06/09', '17/09', '31/10', '31/12', '06/01', '15/03', '27/4'],  
            "event_names" => ['Sabado', 'Jueves Universitario', 'Halloween', 'Noche Vieja', 'Reyes', 'Carnaval', 'Genarin'], 
            "data" => [
                 [
                    "name" => "Usuarios",
                    "data"=> [1300, 2100, 3200, 2300, 1200, 1500, 2700]
                ], 
                [
                    "name" => "Ingresos",
                    "data"=> [1231, 3434, 2320, 1200, 1500, 2700, 3200]
                ], 
            ]
        ];

        $last_event = $this->events()->lastEvent()->first();

        return [
            'uid' => $this->uid,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'average_ticket_price' => $this->average_ticket_price,
            'timezone_uid' => $this->timezone_uid,
            'timezone_string' => $this->timezone->name,
            'next_event' => $this->events()->firstNextEvent()->first(),
            'tickets_count_this_month' => $this->tickets()->ticketsCountThisMonth()->count(),
            'tickets_last_month' => $this->tickets()->ticketsCountLastMonth()->count(),
            'qr_url' => config("filesystems.disks.r2.url") . 'qr/' . $this->uid . '.png',
            'link' => $this->link,
            'users_incomes' => $arrayUsersIncome,
            'last_event' => [
                "total_users" => $last_event->total_users,
                "incomes" => $last_event->total_incomes,
                "hooks" => $last_event->hooks,
                "tickets" => $last_event->tickets()->count(),
                "avg_age" => round($last_event->avg_age),
                "male_female" => $last_event->percents,
                "name" => $last_event->name,
                "date" => date('d/m/Y', strtotime($last_event->st_date)),
            ],
        ];
    }
}
