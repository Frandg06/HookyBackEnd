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
            'gender' => $this->gender,
            'sexual_orientation' => $this->sexual_orientation,
            'premium' => $this->role_id === Role::PREMIUM ? true : false,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'email' => $this->email,
            'born_date' => $this->born_date,
            'description' => $this->description,
            'auto_password' => $this->auto_password,
            $this->mergeWhen($this->event, [
                'like_credits' => $this->likes ?? 0,
                'super_like_credits' => $this->super_likes ?? 0,
            ]),
            'data_complete' => $this->data_complete,
            'data_images' => $this->data_images,
            'complete_register' => $this->complete_register,
            'age' => $this->age,
            'images' => $this->images->map(fn ($image) => [
                'uid' => $image->uid,
                'name' => $image->name,
                'web_url' => $image->web_url,
                'order' => $image->order,
                'type' => $image->type,
            ]),
            'notifications' => [
                ...$this->getNotificationsByType(),
            ],
            'stats' => [
                'events' => $this->events_count,
                'hooks' => $this->hooks_as_user1_count + $this->hooks_as_user2_count,
            ],

        ];
    }
}
