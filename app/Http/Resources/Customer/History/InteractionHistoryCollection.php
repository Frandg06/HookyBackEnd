<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\History;

use Illuminate\Http\Request;
use App\Http\Resources\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class InteractionHistoryCollection extends ResourceCollection
{
    public $collects = InteractionHistoryResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'users' => $this->collection,
            'pagination' => PaginationResource::make($this->resource),
        ];
    }
}
