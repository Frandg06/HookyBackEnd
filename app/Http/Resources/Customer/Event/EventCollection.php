<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Event;

use Illuminate\Http\Request;
use App\Http\Resources\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class EventCollection extends ResourceCollection
{
    public $collects = EventResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'events' => $this->collection,
            'pagination' => PaginationResource::make($this->resource),
        ];
    }
}
