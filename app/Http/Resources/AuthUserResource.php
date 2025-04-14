<?php

namespace App\Http\Resources;

use App\Models\NotificationsType;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class AuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $tz = $this->event->timezone;
        $now = now($tz);

        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'event_uid' => $this->event->uid,
            'company' => $this->event->company->name,
            'gender_id' => $this->gender_id,
            'sexual_orientation_id' => $this->sexual_orientation_id,
            'premium' => $this->role_id == Role::PREMIUM ? true : false,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'email' => $this->email,
            'born_date' => $this->born_date,
            'description' => $this->description,
            'like_credits' => $this->likes,
            'super_like_credits' => $this->super_likes,
            'data_complete' => $this->data_complete,
            'data_images' => $this->data_images,
            'complete_register' => $this->complete_register,
            'age' => $this->age,
            'userImages' => $this->userImages,
            'notifications' => [
                ...$this->getNotificationsByType(),
                'message' => $this->unread_chats
            ],
            'next_event' => [
                'exists' => $now->lt($this->event->st_date) ? true : null,
                'st_date' => $this->event->st_date,
            ]

        ];
    }
}
