<?php

declare(strict_types=1);

namespace App\Dtos;

final class CompleteUserDataDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $name,
        public string $email,
        public string $born_date,
        public string $sexual_orientation,
        public string $gender,
        public ?string $description,
        public array $files,
    ) {}
}
