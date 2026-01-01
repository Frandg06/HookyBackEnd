<?php

declare(strict_types=1);

namespace App\Dtos;

final class UpdateSettingsDto
{
    public function __construct(
        public bool $new_like_notification = true,
        public bool $new_superlike_notification = true,
        public bool $new_message_notification = true,
        public bool $event_start_email = true,
    ) {}
}
