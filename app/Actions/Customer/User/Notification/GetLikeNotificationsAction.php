<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\Notification;

use App\Models\User;
use App\Models\TargetUsers;
use App\Enums\InteractionEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Customer\Notification\LikeMinifiedCollection;

final readonly class GetLikeNotificationsAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, int $page = 1): LikeMinifiedCollection
    {
        return DB::transaction(function () use ($user, $page) {

            $likes = TargetUsers::with([
                'emitter:uid,name',
                'emitter.profilePicture',
                'targetUser:uid,role_id',
                'interaction:id,name',
            ])->where('target_user_uid', $user->uid)
                ->where('event_uid', $user->event->uid)
                ->whereIn('interaction_id', [
                    InteractionEnum::LIKE->toId(),
                    InteractionEnum::SUPERLIKE->toId(),
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(20, ['*'], 'page', $page);

            return LikeMinifiedCollection::make($likes);
        });
    }
}
