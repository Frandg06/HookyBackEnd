<?php

namespace App\Http\Resources;

use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'uid' => $this->uid,
            'name' => $this->name,
            'st_date' => date('Y-m-d H:i', strtotime($this->st_date)),
            'end_date' => date('Y-m-d H:i', strtotime($this->end_date)),
            'timezone' => $this->timezone,
            'likes' => $this->likes,
            'super_likes' => $this->super_likes,
            'colors' => $this->colors,
            'users_count' => $this->users()->count(),
            'incomes' => $this->tickets()->where('redeemed', true)->sum('price'),
            'tickets' => $this->tickets()->where('redeemed', true)->count(),
            'tickets_total' => $this->tickets()->count(),
            'males' => $this->users()->getMales()->count(),
            'females' => $this->users()->getMales()->count(),
            'hooks' => $this->hooks,
            'avg_age' => round($this->avg_age),
            'code' => $this->code,

        ];
    }
}
