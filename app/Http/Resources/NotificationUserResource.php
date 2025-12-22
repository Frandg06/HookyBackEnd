<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\NotificationsType;
use Illuminate\Http\Resources\Json\JsonResource;

final class NotificationUserResource extends JsonResource
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
            'main_image' => $this->emitter_user->images()->first()->web_url,
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
