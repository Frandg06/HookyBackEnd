<?php

namespace App\Http\Resources;

use App\Models\NotificationsType;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $tz = $this->auth_event->event->timezone;
        $now = now($tz);

        return [
            "id" => $this->id,
            "uid" => $this->uid,
            "event_uid" => $this->event_uid,
            "gender_id" => $this->gender_id,
            "sexual_orientation_id" => $this->sexual_orientation_id,
            "premium" => $this->role_id == Role::PREMIUM ? true : false,
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
            "data_interest" => $this->data_interest,
            "complete_register" => $this->complete_register,
            "age" => $this->age,
            "userImages" => $this->userImages->map(function ($image) {
                return [
                    "web_url" => $image->web_url,
                    "order" => $image->order,
                    "type" => $image->type,
                    "size" => $image->size,
                    "name" => $image->name,
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
            "notifications" => [
                'like' => $this->notifications()->where('event_uid', $this->event_uid)->where('type_id', NotificationsType::LIKE_TYPE)->where('read_at', null)->count(),
                'superlike' => $this->notifications()->where('event_uid', $this->event_uid)->where('type_id', NotificationsType::SUPER_LIKE_TYPE)->where('read_at', null)->count(),
                'hook' => $this->notifications()->where('event_uid', $this->event_uid)->where('type_id', NotificationsType::HOOK_TYPE)->where('read_at', null)->count(),
                'message' => 1
            ],
            "next_event" => [
                'exists' => $now->lt($this->auth_event->event->st_date) ? true : null,
                'st_date' => $this->auth_event->event->st_date,
            ]

        ];
    }
}
