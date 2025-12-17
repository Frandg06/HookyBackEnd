<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\TargetUserResource;

final class UserService extends Service
{
    public function getTargetUsers()
    {
        return DB::transaction(function () {
            $auth = $this->user();
            $cacheKey = 'target_users_uids_'.$auth->uid.'_'.$auth->event->uid;
            $cachedUids = Cache::get($cacheKey, []);
            $needed = 100 - count($cachedUids);

            if ($needed > 0) {
                $targetUsers = User::whereTargetUsersFrom($auth)
                    ->whereNotIn('uid', $cachedUids)
                    ->orderBy('created_at', 'asc')
                    ->orderBy('id', 'asc')
                    ->limit($needed)
                    ->get();

                $targetUids = $targetUsers->pluck('uid')->toArray();
                $cachedUids = array_merge($cachedUids, $targetUids);
                Cache::put($cacheKey, $cachedUids);
            }

            $users = User::whereIn('uid', $cachedUids)->get();
            DB::commit();

            return TargetUserResource::collection($users);

        });
    }
}
