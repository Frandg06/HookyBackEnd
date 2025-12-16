<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\DTO\InteractionDto;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final readonly class RemoveTargetUserFromCacheAction
{
    /**
     * Execute the action.
     */
    public function execute(InteractionDto $dto): void
    {
        DB::transaction(function () use ($dto) {
            $cacheKey = 'target_users_uids_'.$dto->user_uid.'_'.$dto->event_uid;
            $cachedUids = Cache::get($cacheKey, []);
            $filtered = collect($cachedUids)
                ->reject(fn ($cachedUid) => $cachedUid === $dto->target_user_uid)
                ->values();
            Cache::put($cacheKey, $filtered->toArray());
        });
    }
}
