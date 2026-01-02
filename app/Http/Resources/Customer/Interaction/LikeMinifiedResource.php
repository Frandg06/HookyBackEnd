<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Interaction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\InteractionEnum;
use Illuminate\Http\Resources\Json\JsonResource;

final class LikeMinifiedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $showFull = $this->targetUser->is_premium || $this->interaction === InteractionEnum::SUPERLIKE;

        return [
            'uid' => $this->id,
            'emitter_uid' => $this->emitter->uid,
            'type' => $this->interaction,
            'time' => Carbon::parse($this->updated_at)->diffForHumans([
                'short' => true,
            ]),
            $this->mergeWhen($showFull, [
                'name' => $this->emitter->name,
                'image' => $this->emitter->profilePicture->web_url,
            ]),
            $this->mergeWhen(! $showFull, [
                'image' => $this->getDefaultImages(),
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
