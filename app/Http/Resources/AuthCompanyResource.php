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
           'timezone_uid' => $this->timezone_uid,
           'timezone_string' => $this->timezone->name,
           'next_event' => $this->events()->where('st_date', '>', Carbon::now())->latest()->first(),
        ];
    }
}
