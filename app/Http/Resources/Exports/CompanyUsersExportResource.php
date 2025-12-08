<?php

declare(strict_types=1);

namespace App\Http\Resources\Exports;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class CompanyUsersExportResource extends JsonResource
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
            'Nombre' => $this->name.' '.$this->surnames,
            'Email' => $this->email,
            'phone' => $this->phone,
            'Instagram' => $this->ig,
            'x' => $this->tw,
            'Edad' => $this->age,
            'Género' => $this->gender_id === 1 ? 'Mujer' : 'Hombre',
            'Rol' => $this->role_id === 2 ? 'Usuario' : 'VIP',
            'Consumo' => 0,
        ];
    }
}
