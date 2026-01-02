<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string,Ticket>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'redeemed' => $this->redeemed,
            'superlikes' => $this->superlikes,
            'likes' => $this->likes,
            'code' => $this->code,
            'event' => $this->event?->name,
        ];
    }
}
