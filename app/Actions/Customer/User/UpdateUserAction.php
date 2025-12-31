<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Models\User;
use App\Dtos\UpdateUserDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\Customer\UserResource;

final readonly class UpdateUserAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, UpdateUserDto $data): UserResource
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name' => $data->name,
                'email' => $data->email,
                'born_date' => $data->born_date,
                'sexual_orientation' => $data->sexual_orientation,
                'gender' => $data->gender,
                'description' => $data->description,
            ]);

            if (isset($data->gender) || isset($data->sexual_orientation)) {
                $user->interactions()->delete();
                $cacheKey = 'target_users_uids_'.$user->uid.'_'.$user->event->uid;
                Cache::forget($cacheKey);
            }

            return UserResource::make($user->loadRelations());
        });
    }
}
