<?php

declare(strict_types=1);

namespace App\Actions\Customer\Image;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Customer\UserResource;
use App\Actions\Customer\Image\DeleteImagePipeline\DeleteImagePipe;
use App\Actions\Customer\Image\StoreImagePipeline\SaveImageDataPipe;
use App\Actions\Customer\Image\StoreImagePipeline\StoreUserImagesPipe;
use App\Actions\Customer\Image\DeleteImagePipeline\DeleteImagePassable;
use App\Actions\Customer\Image\StoreImagePipeline\PrepareToStoreImages;
use App\Actions\Customer\Image\DeleteImagePipeline\TransformPassablePipe;

final readonly class SwapImageAction
{
    /**
     * Execute the action.
     */
    public function execute(DeleteImagePassable $passable): UserResource
    {
        return DB::transaction(function () use ($passable) {
            app(Pipeline::class)
                ->send($passable)
                ->through([
                    DeleteImagePipe::class,
                    TransformPassablePipe::class,
                    PrepareToStoreImages::class,
                    StoreUserImagesPipe::class,
                    SaveImageDataPipe::class,
                ])
                ->thenReturn();

            return UserResource::make($passable->user->loadRelations());
        });
    }
}
