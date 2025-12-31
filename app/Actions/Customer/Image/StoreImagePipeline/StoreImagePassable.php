<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image\StoreImagePipeline;

use App\Models\User;
use App\Dtos\StoreImageDto;

final class StoreImagePassable
{
    public array $imagesToInsert = [];

    public array $tempPaths = [];

    public function __construct(
        public User $user,
        public StoreImageDto $data,
    ) {}
}
