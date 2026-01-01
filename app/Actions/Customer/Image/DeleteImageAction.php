<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image;

use App\Models\User;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\UserImageRepository;
use App\Http\Resources\Customer\UserResource;

final readonly class DeleteImageAction
{
    public function __construct(
        private UserImageRepository $userImageRepository
    ) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, string $uid): UserResource
    {
        return DB::transaction(function () use ($user, $uid) {
            $imageToDelete = $this->userImageRepository->findImageByUid($uid);

            $delete = Storage::disk('r2')->delete($imageToDelete->url);

            if (! $delete) {
                throw new ApiException('i18n.image_delete_ko', 500);
            }

            $deletedOrder = $imageToDelete->order;

            $this->userImageRepository->deleteImage($imageToDelete);
            $this->userImageRepository->decrementImagesOrderByUserUid($user->uid, $deletedOrder);

            return UserResource::make($user->loadRelations());
        });
    }
}
