<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\History;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class InteractionHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'uid' => $this->id,
            'emitter' => $this->whenLoaded('emitter', function () {
                return [
                    'emitter_uid' => $this->emitter->uid,
                    'name' => $this->emitter->name,
                    'image' => $this->emitter->profilePicture->web_url ?? $this->getDefaultImages(),
                ];
            }),
            'reciver' => $this->whenLoaded('targetUser', function () {
                return [
                    'target_user_uid' => $this->targetUser->uid,
                    'name' => $this->targetUser->name,
                    'image' => $this->targetUser->profilePicture->web_url ?? $this->getDefaultImages(),
                ];
            }),
            'type' => $this->interaction,
            'time' => Carbon::parse($this->updated_at)->diffForHumans([
                'short' => true,
            ]),
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
