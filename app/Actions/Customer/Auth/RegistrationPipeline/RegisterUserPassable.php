<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth\RegistrationPipeline;

use App\Models\User;
use App\Dtos\RegisterUserDto;

final class RegisterUserPassable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public RegisterUserDto $userDto,
        public ?string $accessToken = null,
        public ?User $user = null,
    ) {}

}
