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
            "uid" => $this->uid,
            "name" => $this->name,
            "surnames" => $this->surnames,
            "main_image" => $this->userImages()->first()->web_url,
            "time" => Carbon::parse($this->ago)->diffForHumans(),
        ];
    }
}
