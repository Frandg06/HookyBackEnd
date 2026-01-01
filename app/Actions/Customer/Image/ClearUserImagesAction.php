<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image;

use App\Models\User;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Repositories\UserImageRepository;

final readonly class ClearUserImagesAction
{
    public function __construct(
        private UserImageRepository $userImageRepository
    ) {}

    /**
     * Execute the action to delete all images for a user.
     */
    public function execute(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $this->userImageRepository->deleteAllByUserUid($user->uid);

            $deleted = Storage::disk('r2')->deleteDirectory("hooky/profile/$user->uid");

            if (! $deleted) {
                throw new ApiException('i18n.image_delete_ko', 500);
            }

            return true;
        });
    }
}
