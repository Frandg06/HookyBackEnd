<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image\StoreImagePipeline;

use Closure;
use App\Models\UserImage;

final class SaveImageDataPipe
{
    /**
     * Update user images in the database.
     */
    public function handle(StoreImagePassable $passable, Closure $next): StoreImagePassable
    {
        UserImage::insert($passable->imagesToInsert);

        return $next($passable);
    }
}
