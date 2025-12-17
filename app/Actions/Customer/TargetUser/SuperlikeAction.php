<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use App\DTO\InteractionDto;
use App\Models\TargetUsers;
use Illuminate\Support\Facades\DB;
use App\Repositories\NotifyRepository;
use App\Events\SuperlikeNotificationEvent;
use App\Repositories\TargetUserRepository;

final readonly class SuperlikeAction
{
    public function __construct(
        private readonly TargetUserRepository $targetUserRepository,
        private readonly HookAction $hookAction,
        private readonly NotifyRepository $notifyRepository
    ) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, InteractionDto $target): array
    {
        return DB::transaction(function () use ($user, $target) {
            $target_user = User::find($target->target_user_uid);

            $this->targetUserRepository->create($target->toArray());

            $user->activeEvent->first()?->pivot->decrement('super_likes');

            $isHook = TargetUsers::isHook($target)->exists();

            if ($isHook) {
                return $this->hookAction->execute($user, $target_user, $target);
            }

            SuperlikeNotificationEvent::dispatch($user, $target_user);

            $this->notifyRepository->create($target, 2);

            return [
                'super_like_credits' => $user->super_likes,
                'like_credits' => $user->likes,
            ];
        });
    }
}
