<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "uid" => $this->uid,
            "name" => $this->name,
            "surnames" => $this->surnames,
            "gender_id" => $this->gender_id,
            "sexual_orientation_id" => $this->sexual_orientation_id,
            "city" => $this->city,
            "description" => $this->description,
            "age" => $this->age,
            "userImages" => $this->userImages->map(function ($image) {
                return [
                    "web_url" => $image->web_url,
                ];
            }),
            "socials" => [
                "instagram" => [
                    "name" => $this->ig,
                    "url" => "https://www.instagram.com/" . $this->ig
                ],
                "tw" => [
                    "name" => $this->tw,
                    "url" => "https://www.x.com/" . $this->tw
                ]
            ],
            "interests" => $this->interests->map(function ($interest) {
                return [
                    "id" => $interest->interest_id,
                    "name" => $interest->interest->name,
                    "icon" => $interest->interest->icon,
                    "color" => $interest->interest->color,
                    "bg_color" => $interest->interest->bg_color,
                ];
            }),
        ];
    }
}
