<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Models\User;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Customer\UserResource;

final readonly class UpdatePasswordAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, array $data): UserResource
    {
        return DB::transaction(function () use ($user, $data) {
            if ($user->auto_password === false && ! Hash::check($data['old_password'], $user->password)) {
                throw new ApiException('actual_password_ko', 400);
            }

            $user->password = bcrypt($data['password']);
            $user->auto_password = false;
            $user->save();

            return UserResource::make($user->loadRelations());
        });
    }
}
