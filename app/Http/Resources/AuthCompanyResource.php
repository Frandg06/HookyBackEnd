<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AuthCompanyResource extends JsonResource
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
            'cif' => $this->cif,
            'website' => $this->website,
            'timezone_uid' => $this->timezone_uid,
            'timezone_string' => $this->timezone->name,
            'link' => $this->link,
            'users' => $this->total_users,
            'incomes' => $this->incomes,
            'tickets' => $this->tickets->where('redeemed_at', '>=', now()->startOfDay())->count(),
            'last_event' => EventResource::make($this->last_event),
        ];
    }
}
