<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use App\Dtos\InteractionDto;
use Illuminate\Support\Facades\DB;
use App\Enums\NotificationTypeEnum;
use App\Events\SuperlikeNotificationEvent;
use App\Repositories\TargetUserRepository;
use App\Repositories\NotificationRepository;

final readonly class SuperlikeAction
{
    public function __construct(
        private readonly TargetUserRepository $targetUserRepository,
        private readonly HookAction $hookAction,
        private readonly NotificationRepository $notificationRepository
    ) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, InteractionDto $target): array
    {
        return DB::transaction(function () use ($user, $target) {
            $target_user = User::find($target->target_user_uid);

            $this->targetUserRepository->create($target->toArray());

            $user->activeEvent->first()?->pivot->decrement('superlikes');

            $isHook = $this->targetUserRepository->canMakeHook($target);

            if ($isHook) {
                return $this->hookAction->execute($user, $target_user, $target);
            }

            SuperlikeNotificationEvent::dispatch($user, $target_user);

            $this->notificationRepository->create($target, NotificationTypeEnum::SUPERLIKE);

            return [
                'superlike_credits' => $user->superlikes,
                'like_credits' => $user->likes,
            ];
        });
    }
}
