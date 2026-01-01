<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UserImage;
use App\Exceptions\NotFoundException;

final class UserImageRepository
{
    public function findImageByUid(string $uid): UserImage
    {
        $image = UserImage::where('uid', $uid)->first();

        throw_if(! $image, NotFoundException::image());

        return $image;
    }

    public function deleteImage(UserImage $image): void
    {
        $image->delete();
    }

    public function decrementImagesOrderByUserUid(string $user_uid, int $order): void
    {
        UserImage::where('user_uid', $user_uid)
            ->where('order', '>', $order)
            ->decrement('order');
    }
}
