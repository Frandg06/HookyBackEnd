<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatPreviewResource extends JsonResource
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
            'user1' => [
                'uid' => $this->user1->uid,
                'name' => $this->user1->name,
                'image' => $this->user1->userImages()->first()->web_url,
            ],
            'user2' => [
                'uid' => $this->user2->uid,
                'name' => $this->user2->name,
                'image' => $this->user2->userImages()->first()->web_url,
            ],
            'event_uid' => $this->event_uid,
            'last_message' => [
                'message' => $this->lastMessage()->message,
                'sender_uid' => $this->lastMessage()->sender_uid,
                'read_at' => $this->lastMessage()->read_at,
                'created_at' => Carbon::parse($this->lastMessage()->created_at)->diffForHumans(),
            ],
        ];
    }
}
