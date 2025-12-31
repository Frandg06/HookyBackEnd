<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\Notification;

use App\Models\Hook;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Customer\Interaction\HookMinifiedCollection;

final readonly class GetHookNotificationsAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, int $page = 1): HookMinifiedCollection
    {
        return DB::transaction(function () use ($user, $page) {

            $hooks = Hook::with([
                'user1:uid,name',
                'user1.profilePicture',
                'user2:uid,name',
                'user2.profilePicture',
                'event:uid,name',
            ])->whereAny(['user1_uid', 'user2_uid'], $user->uid)
                ->where('event_uid', $user->event->uid)
                ->orderBy('created_at', 'desc')
                ->orderBy('uid', 'desc')
                ->paginate(20, ['*'], 'page', $page);

            return HookMinifiedCollection::make($hooks);
        });
    }
}
