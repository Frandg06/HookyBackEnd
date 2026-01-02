<?php

declare(strict_types=1);

namespace App\Http\Resources\Exports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class EventExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Id' => $this->id,
            'Nombre' => $this->name,
            'Fecha_inicio' => $this->st_date,
            'Fecha_final' => $this->end_date,
            'Likes' => $this->likes,
            'Superlikes' => $this->superlikes,
            'Registros' => $this->users()->count(),
            'Tickets_Canjeados' => $this->tickets()->where('redeemed', true)->count(),
            'Hombres' => $this->users()->getMales()->count(),
            'Mujeres' => $this->users()->getMales()->count(),
        ];
    }
}
