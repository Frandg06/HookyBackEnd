<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\CompleteUserPipeline;

use App\Models\User;
use App\Dtos\CompleteUserDataDto;

final class CompleteUserDataPassable
{
    public array $imagesToInsert = [];

    public array $tempPaths = [];

    public function __construct(
        public User $user,
        public CompleteUserDataDto $data,
    ) {}
}
