<?php

namespace App\Http\Resources;

use App\Models\Role;
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
            'id' => $this->id,
            'uid' => $this->uid,
            'company' => [
                'uid' => optional($this->company)->uid,
                'name' =>  optional($this->company)->name,
            ],
            'event' => [
                'is_active' => optional($this->event)->is_active,
                'uid' => optional($this->event)->uid,
                'name' => optional($this->event)->name,
                'st_date' => optional($this->event)->st_date,
                'end_date' => optional($this->event)->end_date,
                'is_finished' => optional($this->event)->is_finished,

            ],
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
            ],
        ];
    }
}
