<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class PaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->currentPage(),
            'next_page' => $this->currentPage() + 1 > $this->lastPage() ? null : $this->currentPage() + 1,
            'total_pages' => $this->lastPage(),
            'prev_page' => $this->currentPage() - 1 < 1 ? null : $this->currentPage() - 1,
        ];
    }
}
