<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'name' => $this->name,
            'surnames' => $this->surnames,
            'gender_id' => $this->gender_id,
            'gender_name' => $this->gender->name,
            'sexual_orientation_id' => $this->sexual_orientation_id,
            'sexual_orientation_name' => $this->sexualOrientation->name,
            'description' => $this->description,
            'age' => $this->age,
            'userImages' => $this->userImages->pluck('web_url'),
        ];
    }
}
