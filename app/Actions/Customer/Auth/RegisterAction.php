<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Dtos\RegisterUserDto;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Actions\Customer\Auth\RegistrationPipeline\RegisterUserPassable;

final readonly class RegisterAction
{
    /**
     * Execute the action.
     */
    public function execute(RegisterUserDto $data): string
    {
        return DB::transaction(function () use ($data): string {
            $passable = new RegisterUserPassable(userDto: $data);
            app(Pipeline::class)
                ->send($passable)
                ->through([
                    RegistrationPipeline\RegisterUserPipe::class,
                    RegistrationPipeline\AttachEventPipe::class,
                ])
                ->thenReturn();

            return $passable->accessToken;
        });
    }
}
