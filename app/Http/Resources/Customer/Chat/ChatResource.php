<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Chat;

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

        $auth = $request->user()->uid;
        $chat_user = $auth === $this->user1_uid ? $this->user2 : $this->user1;

        return [
            'uid' => $this->uid,
            'chat_user' => [
                'uid' => $chat_user->uid,
                'name' => $chat_user->name,
                'image' => $chat_user->profilePicture->web_url,
            ],
            'event_uid' => $this->event_uid,
        ];
    }
}
