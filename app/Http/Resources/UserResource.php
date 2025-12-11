<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
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
                'uid' => $this->company?->uid,
                'name' => $this->company?->name,
            ],
            'event' => $this->when(
                $this->event,
                [
                    'is_active' => $this->event?->is_active,
                    'uid' => $this->event?->uid,
                    'name' => $this->event?->name,
                    'st_date' => $this->event?->st_date,
                    'end_date' => $this->event?->end_date,
                    'is_finished' => $this->event?->is_finished,

                ]
            ),
            'company_event' => $this->when(
                ! $this->event && $this->event,
                [
                    'is_active' => $this->event?->is_active,
                    'uid' => $this->event?->uid,
                    'name' => $this->event?->name,
                    'st_date' => $this->event?->st_date,
                    'end_date' => $this->event?->end_date,
                    'is_finished' => $this->event?->is_finished,

                ]
            ),
            'gender_id' => $this->gender_id,
            'sexual_orientation_id' => $this->sexual_orientation_id,
            'premium' => $this->role_id === Role::PREMIUM ? true : false,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'email' => $this->email,
            'born_date' => $this->born_date,
            'description' => $this->description,
            $this->mergeWhen($this->event, [
                'like_credits' => $this->likes ?? 0,
                'super_like_credits' => $this->super_likes ?? 0,
            ]),
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
