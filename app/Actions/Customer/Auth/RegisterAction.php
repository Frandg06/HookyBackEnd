<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Exceptions\ApiException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
final readonly class RegisterAction
{

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * Execute the action.
     */
    public function execute(array $data): string
    {
        return DB::transaction(function () use ($data): string {
            $user = $this->userRepository->createUser($data);

            $token = Auth::login($user);

            if (! $token) {
                throw new ApiException('credentials_ko', 401);
            }

            return $token;
        });
    }
}
