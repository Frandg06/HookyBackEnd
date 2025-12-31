<?php

declare(strict_types=1);

namespace App\Dtos;

final class StoreImageDto
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public array $files,
    ) {}
}
