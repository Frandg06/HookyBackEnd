<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\Notification;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use App\Enums\NotificationTypeEnum;
use App\Http\Resources\NotificationElementResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class GetLikeNotificationsAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user): AnonymousResourceCollection
    {
        return DB::transaction(function () use ($user) {

            $likes = Notification::with([
                'emitter_user:uid,name',
                'emitter_user.profilePicture',
                'user:uid,role_id',
                'type:id,name',
            ])->where('user_uid', $user->uid)
                ->whereIn('type_id', [
                    NotificationTypeEnum::LIKE->toId(),
                    NotificationTypeEnum::SUPERLIKE->toId(),
                ])
                ->where('event_uid', $user->event->uid)
                ->orderBy('created_at', 'desc')
                ->get();

            return NotificationElementResource::collection($likes);
        });
    }
}
