<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image\StoreImagePipeline;

use Closure;
use Spatie\Image\Image;
use Illuminate\Support\Str;

final class PrepareToStoreImages
{
    /**
     * Check if a user is changing their email address to another one that already exists in the database.
     */
    public function handle(StoreImagePassable $passable, Closure $next): StoreImagePassable
    {
        foreach ($passable->data->files as $index => $file) {
            $data = [
                'uid' => Str::uuid(),
                'order' => $passable->user->images->count() + $index + 1,
                'name' => $file->getClientOriginalName() === 'blob' ? 'IMG_'.uniqid() : $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'user_uid' => $passable->user->uid,
            ];

            $tempPath = [
                'tmp_path' => sys_get_temp_dir().'/'.uniqid().'.webp',
                'webp_path' => 'hooky/profile/'.$passable->user->uid.'/'.$data['uid'].config('filesystems.disks.r2.image_default_extension'),
            ];

            Image::load($file->getPathname())
                ->width(500)
                ->format('webp')
                ->optimize()
                ->save($tempPath['tmp_path']);

            $passable->imagesToInsert[] = $data;
            $passable->tempPaths[] = $tempPath;

        }

        return $next($passable);
    }
}
