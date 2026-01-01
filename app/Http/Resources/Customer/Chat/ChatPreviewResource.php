<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Chat;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ChatPreviewResource extends JsonResource
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
                'image' => $this->user1->profilePicture->web_url,
            ],
            'user2' => [
                'uid' => $this->user2->uid,
                'name' => $this->user2->name,
                'image' => $this->user2->profilePicture->web_url,
            ],
            'event_uid' => $this->event_uid,
            'last_message' => $this->lastMessage() ? [
                'message' => $this->lastMessage()->message,
                'sender_uid' => $this->lastMessage()->sender_uid,
                'read_at' => $this->lastMessage()->read_at,
                'created_at' => Carbon::parse($this->lastMessage()->created_at)->diffForHumans(),
            ] : null,
        ];
    }
}
