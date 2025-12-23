<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image\StoreImagePipeline;

use Closure;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Storage;

final class StoreUserImagesPipe
{
    /**
     * Update user images in the database.
     */
    public function handle(StoreImagePassable $passable, Closure $next): StoreImagePassable
    {
        foreach ($passable->tempPaths as $tempPath) {
            $stream = fopen($tempPath['tmp_path'], 'r');

            $storage = Storage::disk('r2')->put($tempPath['webp_path'], $stream);

            if (! $storage) {
                throw new ApiException('images_store_ko', 500);
            }
        }

        return $next($passable);
    }
}
