<?php

declare(strict_types=1);

namespace App\Dtos;

final class RegisterUserDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $eventUid = null,
    ) {}
}
