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
            "uid" => $this->user->uid,
            "name" => $this->user->name,
            "surnames" => $this->user->surnames,
            "main_image" => $this->user->userImages()->first()->web_url,
            "time" => Carbon::parse($this->user->ago)->diffForHumans(),
        ];
    }
}
