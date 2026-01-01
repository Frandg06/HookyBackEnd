<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image\DeleteImagePipeline;

use Closure;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\UserImageRepository;

final readonly class DeleteImagePipe
{
    public function __construct(
        private UserImageRepository $userImageRepository
    ) {}

    /**
     * Execute the action.
     */
    public function handle(DeleteImagePassable $passable, Closure $next): mixed
    {
        return DB::transaction(function () use ($passable, $next) {
            $imageToDelete = $this->userImageRepository->findImageByUid($passable->image_uid);

            $delete = Storage::disk('r2')->delete($imageToDelete->url);

            throw_if(! $delete, new ApiException('i18n.image_delete_ko', 500));

            $deletedOrder = $imageToDelete->order;

            $this->userImageRepository->deleteImage($imageToDelete);
            $this->userImageRepository->decrementImagesOrderByUserUid($passable->user->uid, $deletedOrder);

            return $next($passable);
        });
    }
}
