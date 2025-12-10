<?php
declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Actions\Customer\AttachUserToCompanyEvent;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Socialite;

final readonly class SocialLoginAction
{
    public function __construct(
        private readonly AttachUserToCompanyEvent $attachUserToCompanyEvent,
        private readonly UserRepository $userRepository,
    ) {}
    
    public function execute(string $accessToken, string $provider): string
    {
        return DB::transaction(function () use ($accessToken, $provider) {
            /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
            $driver = Socialite::driver($provider);
            $socialUser = $driver->userFromToken($accessToken);

            $user = $this->userRepository->updateOrCreateUserFromSocialLogin($socialUser, $provider);
        
            $token = Auth::login($user);

            return $token;

        });
    }
}