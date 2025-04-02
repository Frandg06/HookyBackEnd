<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class EventUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'email' => $this->email,
            'ig' => $this->ig,
            'tw' => $this->tw,
            'age' => $this->age,
            'role_id' => $this->role_id,
            'gender_id' => $this->gender_id,
            'consumption' => $this->tickets()
                ->where('event_uid', $request->event_uid)
                ->where('redeemed', true)
                ->sum('price'),
        ];
    }
}
