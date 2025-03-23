<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uid' => $this->emitter_user->uid,
            'name' => $this->emitter_user->name,
            'surnames' => $this->emitter_user->surnames,
            'main_image' => $this->emitter_user->userImages()->first()->web_url,
            'time' => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}
