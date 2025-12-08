<?php

declare(strict_types=1);

namespace App\Http\Resources\Exports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TicketExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ID' => $this->id,
            'Nombre' => $this->name,
            'Precio' => $this->price,
            'Canjeado' => $this->redeemed ? 'Si' : 'No',
            'Superlikes' => $this->super_likes,
            'Likes' => $this->likes,
            'Codigo' => $this->code,
            'Evento' => $this->event?->name,
        ];
    }
}
