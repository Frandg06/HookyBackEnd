<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TargetUserResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'age' => $this->age,
            'images' => $this->images->pluck('web_url'),
            $this->mergeWhen($this->interaction, [
                'interaction' => $this->interaction,
            ]),
        ];
    }
}
