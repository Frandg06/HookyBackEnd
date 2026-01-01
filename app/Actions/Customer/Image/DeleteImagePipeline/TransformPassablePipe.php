<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image\DeleteImagePipeline;

use Closure;
use App\Dtos\StoreImageDto;
use App\Actions\Customer\Image\StoreImagePipeline\StoreImagePassable;

final class TransformPassablePipe
{
    public function handle(DeleteImagePassable $passable, Closure $next): mixed
    {
        $storeImageDto = new StoreImageDto(
            files: [$passable->file],
        );

        $storeImagePassable = new StoreImagePassable(
            user: $passable->user,
            data: $storeImageDto,
        );

        return $next($storeImagePassable);
    }
}
