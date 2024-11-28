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
           'next_month_events' => $this->events()->nextMontEvents()->get(),
           'tickets' => $this->tickets()->limit(5)->orderBy('redeemed_at', 'desc')->get(),
           'tickets_count_this_month' => $this->tickets()->ticketsCountThisMonth()->count(),
           'tickets_last_month' => $this->tickets()->ticketsCountLastMonth()->count(),
           'qr_url' => config("filesystems.disks.r2.url") . 'qr/' . $this->uid . '.png',
        ];
    }
}
