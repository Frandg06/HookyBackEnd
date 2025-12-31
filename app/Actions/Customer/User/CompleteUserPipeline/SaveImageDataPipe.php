<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\CompleteUserPipeline;

use Closure;
use App\Models\UserImage;

final class SaveImageDataPipe
{
    /**
     * Update user images in the database.
     */
    public function handle(CompleteUserDataPassable $passable, Closure $next): CompleteUserDataPassable
    {
        UserImage::insert($passable->imagesToInsert);

        return $next($passable);
    }
}
