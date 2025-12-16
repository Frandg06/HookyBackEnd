<?php

declare(strict_types=1);

namespace App\DTO;

final class InteractionDto
{
    public string $user_uid;

    public string $target_user_uid;

    public string $event_uid;

    public int $interaction_id;

    public function __construct(string $user_uid, string $target_user_uid, string $event_uid, int $interaction_id)
    {
        $this->user_uid = $user_uid;
        $this->target_user_uid = $target_user_uid;
        $this->event_uid = $event_uid;
        $this->interaction_id = $interaction_id;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['user_uid'],
            $data['target_user_uid'],
            $data['event_uid'],
            $data['interaction_id'],
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
