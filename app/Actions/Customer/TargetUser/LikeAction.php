<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use App\Dtos\InteractionDto;
use Illuminate\Support\Facades\DB;
use App\Enums\NotificationTypeEnum;
use App\Events\LikeNotificationEvent;
use App\Repositories\TargetUserRepository;
use App\Repositories\NotificationRepository;

final readonly class LikeAction
{
    public function __construct(
        private readonly TargetUserRepository $targetUserRepository,
        private readonly NotificationRepository $notificationRepository,
        private HookAction $hookAction
    ) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, InteractionDto $target): array
    {
        return DB::transaction(function () use ($user, $target) {
            $target_user = User::find($target->target_user_uid);

            $this->targetUserRepository->create($target->toArray());

            $user->activeEvent->first()?->pivot->decrement('likes');

            $isHook = $this->targetUserRepository->canMakeHook($target);

            if ($isHook) {
                return $this->hookAction->execute($user, $target_user, $target);
            }

            LikeNotificationEvent::dispatch($user, $target_user);

            $this->notificationRepository->create($target, NotificationTypeEnum::LIKE);

            return [
                'super_like_credits' => $user->super_likes,
                'like_credits' => $user->likes,
            ];
        });
    }
}
