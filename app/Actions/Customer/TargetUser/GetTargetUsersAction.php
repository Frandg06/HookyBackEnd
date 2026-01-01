<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\TargetUserResource;
use App\Repositories\TargetUserRepository;

final readonly class GetTargetUsersAction
{
    public function __construct(private readonly TargetUserRepository $targetUserRepository) {}

    /**
     * Execute the action.
     */
    public function execute(User $user)
    {
        return DB::transaction(function () use ($user) {

            $cached_uids = Cache::get($user->target_users_cache_key, []);
            $needed = 100 - count($cached_uids);

            $users = $this->targetUserRepository->getTargetUsersFromUids($cached_uids);

            if ($needed > 0) {
                $new_target_users = $this->targetUserRepository->getTargetUsers($user);
                $new_target_users_uids = $new_target_users->pluck('uid')->toArray();
                $new_cached_uids = array_merge($cached_uids, $new_target_users_uids);
                Cache::put($user->target_users_cache_key, $new_cached_uids);
                $users = $users->merge($new_target_users);
            }

            return TargetUserResource::collection($users);

        });
    }
}
