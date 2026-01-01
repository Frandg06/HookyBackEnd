<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Customer\UserResource;

final readonly class UpdatePasswordAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * Execute the action.
     */
    public function execute(User $user, array $data): UserResource
    {
        return DB::transaction(function () use ($user, $data) {
            if ($user->auto_password === false && ! $this->userRepository->checkPassword($user, $data['old_password'])) {
                throw new ApiException('actual_password_ko', 400);
            }

            $this->userRepository->updateUserPassword($user, $data['password']);
            $this->userRepository->updateUserAutoPassword($user, false);

            return UserResource::make($user->loadRelations());
        });
    }
}
