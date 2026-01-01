<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\CompleteUserPipeline;

use Closure;
use App\Dtos\StoreImageDto;
use App\Actions\Customer\Image\StoreImagePipeline\StoreImagePassable;

final class TransformPassablePipe
{
    public function handle(CompleteUserDataPassable $passable, Closure $next): mixed
    {
        $storeImageDto = new StoreImageDto(
            files: $passable->data->files,
        );

        $storeImagePassable = new StoreImagePassable(
            user: $passable->user,
            data: $storeImageDto,
        );

        return $next($storeImagePassable);
    }
}
