<?php

namespace App\Http\Resources;

use App\Models\NotificationsType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uid' => $this->emitter_user->uid,
            'name' => $this->emitter_user->name,
            'surnames' => $this->emitter_user->surnames,
            'main_image' => $this->emitter_user->userImages()->first()->web_url,
            'time' => Carbon::parse($this->updated_at)->diffForHumans([
                'short' => true,
            ]),
            $this->mergeWhen($this->type_id === NotificationsType::HOOK_TYPE, function () {
                $chat = $this->emitter_user->chats()
                    ->whereAny(['user1_uid', 'user2_uid'], $this->emitter_user->uid)
                    ->first();

                return [
                    'chat' => optional($chat)->uid,
                ];
            }),
        ];
    }
}
