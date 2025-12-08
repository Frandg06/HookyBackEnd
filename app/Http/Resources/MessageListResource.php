<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class MessageListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reciber_uid' => $this->emitter_user->uid,
            'name' => $this->emitter_user->name,
            'surnames' => $this->emitter_user->surnames,
            'avatar' => $this->emitter_user->userImages->first()->web_url,
        ];
    }
}
