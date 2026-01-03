<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth\RegistrationPipeline;

use Closure;
use App\Exceptions\ApiException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

final class RegisterUserPipe
{
    public function __construct(private readonly UserRepository $userRepository) {}

    /**
     * Handle the registration passable.
     */
    public function handle(RegisterUserPassable $passable, Closure $next): RegisterUserPassable
    {
        $user = $this->userRepository->createUser([
            'name' => $passable->userDto->name,
            'email' => $passable->userDto->email,
            'password' => bcrypt($passable->userDto->password),
        ]);

        $token = Auth::login($user);

        if (! $token) {
            throw new ApiException('credentials_ko', 401);
        }

        $passable->accessToken = $token;
        $passable->user = $user;

        return $next($passable);
    }
}
