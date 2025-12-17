<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use App\DTO\InteractionDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\ChatRepository;
use App\Events\HookNotificationEvent;
use App\Repositories\NotifyRepository;

final readonly class HookAction
{
    public function __construct(
        private readonly ChatRepository $chatRepository,
        private readonly NotifyRepository $notifyRepository
    ) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, User $targeUser, InteractionDto $target): array
    {
        return DB::transaction(function () use ($user, $targeUser, $target) {

            $chat = $this->chatRepository->store($user->uid, $targeUser->uid, $target->event_uid);

            HookNotificationEvent::dispatch($user, $targeUser, $target->event_uid, $chat->uid);

            $this->notifyRepository->createBoth($target, 3);

            return [
                'super_like_credits' => $user->super_likes,
                'like_credits' => $user->likes,
            ];
        });
    }
}
