<?php

declare(strict_types=1);

namespace App\Dtos;

final class InteractionDto
{
    public function __construct(
        public string $user_uid,
        public string $target_user_uid,
        public string $event_uid,
        public ?int $interaction_id
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['user_uid'],
            $data['target_user_uid'],
            $data['event_uid'],
            $data['interaction_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'user_uid' => $this->user_uid,
            'target_user_uid' => $this->target_user_uid,
            'event_uid' => $this->event_uid,
            'interaction_id' => $this->interaction_id,
        ];
    }
}
