<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Event;

class ChartUserAndIncomesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string,Event>
     */
    public function toArray(Request $request): array
    {
        return [
            'labels' => $this->pluck('st_date')->map(function ($date) {
                return Carbon::parse($date)->format('d/m/y');
            }),
            'event_names' => $this->pluck('name')->toArray(),
            'data' => [
                [
                    'name' => 'Usuarios',
                    'data' => $this->pluck('users2_count')->toArray()
                ],
                [
                    'name' => 'Ingresos',
                    'data' => $this->map(function ($event) {
                        return $event->total_incomes;
                    })->values()->toArray()
                ],
            ]
        ];
    }
}
