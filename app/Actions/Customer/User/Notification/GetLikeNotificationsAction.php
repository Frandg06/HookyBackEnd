<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\Notification;

use App\Models\User;
use App\Models\TargetUsers;
use App\Enums\InteractionEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\LikeMinifiedResource;

final readonly class GetLikeNotificationsAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user, int $page = 1): array
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

            return [
                'likes' => LikeMinifiedResource::collection($likes),
                'pagination' => [
                    'current_page' => $likes->currentPage(),
                    'next_page' => $likes->currentPage() + 1 > $likes->lastPage() ? null : $likes->currentPage() + 1,
                    'total_pages' => $likes->lastPage(),
                    'prev_page' => $likes->currentPage() - 1 < 1 ? null : $likes->currentPage() - 1,
                ],
            ];
        });
    }
}
