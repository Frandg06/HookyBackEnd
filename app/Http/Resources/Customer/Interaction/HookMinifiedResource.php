<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Interaction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class HookMinifiedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $auth = auth()->guard('api')->user();
        $emitter = $this->user1_uid === $auth->uid ? $this->user2 : $this->user1;
        $receiver = $this->user1_uid === $auth->uid ? $this->user1 : $this->user2;

        return [
            'uid' => $this->uid,
            'emitter_uid' => $emitter->uid,
            'type' => 'hook',
            'time' => Carbon::parse($this->updated_at)->diffForHumans([
                'short' => true,
            ]),
            'name' => $emitter->name,
            'image' => $emitter->profilePicture->web_url ?? $this->getDefaultImages(),
            'chat' => $receiver->chats()
                ->whereAny(['user1_uid', 'user2_uid'], $emitter->uid)
                ->where('event_uid', $this->event->uid)
                ->value('uid'),
        ];
    }

    private function getDefaultImages()
    {
        $gender = ['men', 'women'];
        $rand = rand(1, 50);
        $photo = "https://randomuser.me/api/portraits/{$gender[array_rand($gender)]}/{$rand}.jpg";

        return $photo;
    }
}
