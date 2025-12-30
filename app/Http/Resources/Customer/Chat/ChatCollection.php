<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Chat;

use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;
use App\Http\Resources\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class ChatCollection extends ResourceCollection
{
    public $collects = MessageResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'chat' => ChatResource::make($this->resource->first()->chat),
            'messages' => $this->collection,
            'pagination' => PaginationResource::make($this->resource),
        ];
    }
}
