<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReosurce extends JsonResource
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
            "company_id" => $this->company_id,
            "gender_id" => $this->gender_id,
            "sexual_orientation_id" => $this->sexual_orientation_id,
            "name" => $this->name,
            "surnames" => $this->surnames,
            "email" => $this->email,
            "city" => $this->city,
            "born_date" => $this->born_date,
            "description" => $this->description,
            "like_credits" => $this->like_credits,
            "super_like_credits" => $this->super_like_credits,
            "data_complete" => $this->data_complete,
            "data_images" => $this->data_images,
            "age" => $this->age,
            "userImages" => $this->userImages->map(function ($image) {
                return [
                    "web_url" => $image->web_url,
                    "order" => $image->order,
                    "extension" => $image->extension,
                    "uid" => $image->uid,
                ] ;
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
            ]
        ];
    }
}
