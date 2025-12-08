<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ChatResource extends JsonResource
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
            'messages' => MessageResource::collection($this->messages()->orderBy('created_at', 'desc')->get()),
        ];
    }
}
