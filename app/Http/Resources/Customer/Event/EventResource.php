<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Event;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class EventResource extends JsonResource
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
            'name' => $this->name,
            'st_date' => $this->st_date->format('Y-m-d H:i'),
            'end_date' => $this->end_date->format('Y-m-d H:i'),
            'room_name' => $this->room_name,
            'city' => $this->city,
            'banner_image' => $this->banner_image,
            'is_active' => $this->is_active,
            'is_sheduled' => ! $this->is_active && ! $this->is_finished,
            'is_notified' => $this->scheduledNotifications->where('user_uid', request()->user()->uid)->first() ? true : false,
            'users_count' => $this->users2_count ?? 0,
            'diffForHumans' => $this->st_date->diffForHumans(),
            'slug' => $this->slug,
            'description' => $this->description,
            'music_genre' => $this->music_genre,
            'dress_code' => $this->dress_code,
            'entry_fee' => $this->entry_fee,
            'entry_url' => $this->entry_url,
            'has_event_details' => $this->description || $this->music_genre || $this->dress_code || $this->entry_fee || $this->entry_url,
        ];
    }
}
