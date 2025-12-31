<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Chat;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Resources\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class ChatCollection extends ResourceCollection
{
    public $collects = MessageResource::class;

    private mixed $chatData = null;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'messages' => $this->collection,
            'pagination' => PaginationResource::make($this->resource),
            $this->mergeWhen($this->chatData, function () {
                return [
                    'chat' => ChatResource::make($this->chatData),
                ];
            }),
        ];
    }

    public function withChat(Chat $chat): self
    {
        $this->chatData = $chat;

        return $this;
    }
}
