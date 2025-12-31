<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Chat;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
// Asumiré que tienes un ChatResource, si no, puedes usar el modelo directo o crear uno.
use App\Http\Resources\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Customer\Chat\ChatResource as ChatChatResource;

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
                    'chat' => ChatChatResource::make($this->chatData),
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
