<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersToTableResource extends JsonResource
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
            'event_uid' => $this->event_uid,
            'gender_id' => $this->gender_id,
            'sexual_orientation_id' => $this->sexual_orientation_id,
            'role' => $this->role->name,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'email' => $this->email,
            'city' => $this->city,
            'born_date' => $this->born_date,
            'description' => $this->description,
            'like_credits' => $this->event->likes,
            'super_like_credits' => $this->event->super_likes,
            'age' => $this->age,
            'avatar' => $this->userImages()->first()->web_url ?? null,
            'socials' => [
                'instagram' => [
                    'name' => $this->ig,
                    'url' => 'https://www.instagram.com/' . $this->ig
                ],
                'tw' => [
                    'name' => $this->tw,
                    'url' => 'https://www.x.com/' . $this->tw
                ]
            ],
        ];
    }
}
