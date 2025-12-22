<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Models\User;
use App\Dtos\CompleteUserDataDto;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Actions\Customer\User\CompleteUserPipeline\UpdateUserPipe;
use App\Actions\Customer\User\CompleteUserPipeline\SaveImageDataPipe;
use App\Actions\Customer\User\CompleteUserPipeline\StoreUserImagesPipe;
use App\Actions\Customer\User\CompleteUserPipeline\PrepareToStoreImages;
use App\Actions\Customer\User\CompleteUserPipeline\CompleteUserDataPassable;

final class CompleteUserAction
{
    public function execute(User $user, CompleteUserDataDto $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $passable = new CompleteUserDataPassable($user->loadRelations(), $data);

            app(Pipeline::class)
                ->send($passable)
                ->through([
                    UpdateUserPipe::class,
                    PrepareToStoreImages::class,
                    StoreUserImagesPipe::class,
                    SaveImageDataPipe::class,
                ])
                ->thenReturn();

            return $passable->user;
        });

    }
}
