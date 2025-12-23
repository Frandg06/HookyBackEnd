<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image;

use App\Models\User;
use App\Dtos\StoreImageDto;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Actions\Customer\Image\StoreImagePipeline\SaveImageDataPipe;
use App\Actions\Customer\Image\StoreImagePipeline\StoreImagePassable;
use App\Actions\Customer\Image\StoreImagePipeline\StoreUserImagesPipe;
use App\Actions\Customer\Image\StoreImagePipeline\PrepareToStoreImages;

final class StoreImageAction
{
    public function execute(User $user, StoreImageDto $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $passable = new StoreImagePassable($user->load('images'), $data);

            app(Pipeline::class)
                ->send($passable)
                ->through([
                    PrepareToStoreImages::class,
                    StoreUserImagesPipe::class,
                    SaveImageDataPipe::class,
                ])
                ->thenReturn();

            return $passable->user;
        });

    }
}
