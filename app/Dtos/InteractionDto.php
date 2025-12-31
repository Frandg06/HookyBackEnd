<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Enums\InteractionEnum;

final class InteractionDto
{
    public function __construct(
        public string $user_uid,
        public string $target_user_uid,
        public string $event_uid,
        public ?InteractionEnum $interaction
    ) {}

    public function toArray(): array
    {
        return [
            'user_uid' => $this->user_uid,
            'target_user_uid' => $this->target_user_uid,
            'event_uid' => $this->event_uid,
            'interaction' => $this->interaction,
        ];
    }
}
