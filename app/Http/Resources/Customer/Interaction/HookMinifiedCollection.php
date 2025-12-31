<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer\Interaction;

use Illuminate\Http\Request;
use App\Http\Resources\PaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class HookMinifiedCollection extends ResourceCollection
{
    public $collects = HookMinifiedResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'hooks' => $this->collection,
            'pagination' => PaginationResource::make($this->resource),
        ];
    }
}
