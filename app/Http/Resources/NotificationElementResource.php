<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\NotificationTypeEnum;
use Illuminate\Http\Resources\Json\JsonResource;

final class NotificationElementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $showFull = $this->user->is_premium || in_array($this->type->name, [
            NotificationTypeEnum::HOOK->label(),
            NotificationTypeEnum::SUPERLIKE->label(),
        ]);

        return [
            'uid' => $this->uid,
            'emitter_user_uid' => $this->emitter_user->uid,
            'type' => $this->type->name,
            'time' => Carbon::parse($this->updated_at)->diffForHumans([
                'short' => true,
            ]),
            $this->mergeWhen($showFull, [
                'name' => $this->emitter_user->name,
                'image' => $this->emitter_user->profilePicture->first()->web_url,
            ]),
            $this->mergeWhen(! $showFull, [
                'image' => $this->getDefaultImages(),
            ]),
            $this->mergeWhen($this->type->name === NotificationTypeEnum::HOOK->label(), function () {
                $chat = $this->emitter_user->chats()
                    ->whereAny(['user1_uid', 'user2_uid'], $this->emitter_user->uid)
                    ->first();

                return [
                    'chat' => optional($chat)->uid,
                ];
            }),
        ];
    }

    private function getDefaultImages()
    {
        $gender = [
            'men', 'women',
        ];

        $rand = rand(1, 50);

        $photo = "https://randomuser.me/api/portraits/{$gender[array_rand($gender)]}/{$rand}.jpg";

        return $photo;
    }
}
