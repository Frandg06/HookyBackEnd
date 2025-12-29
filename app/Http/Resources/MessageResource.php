<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $auth = $request->user()->uid;

        return [
            'uid' => $this->uid,
            'chat_uid' => $this->chat_uid,
            'is_sender' => $auth === $this->sender_uid,
            'message' => $this->message,
            'read_at' => $this->read_at,
            'created_at' => Carbon::parse($this->created_at)->format('H:i'),
        ];
    }
}
