<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\TargetUsers;

final class TargetUserRepository
{
    public function create(array $data): TargetUsers
    {
        return TargetUsers::create($data);
    }
}
