<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image\DeleteImagePipeline;

use App\Models\User;

final class DeleteImagePassable
{
    public function __construct(
        public User $user,
        public string $image_uid,
        public $file,
    ) {}
}
