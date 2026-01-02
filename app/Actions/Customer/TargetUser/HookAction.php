<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use App\Dtos\InteractionDto;
use Illuminate\Support\Facades\DB;
use App\Enums\NotificationTypeEnum;
use App\Repositories\ChatRepository;
use App\Repositories\HookRepository;
use App\Events\HookNotificationEvent;
use App\Repositories\NotificationRepository;

final readonly class HookAction
{
    public function __construct(
        private readonly ChatRepository $chatRepository,
        private readonly NotificationRepository $notificationRepository,
        private readonly HookRepository $hookRepository,
    ) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, User $targeUser, InteractionDto $target): array
    {
        return DB::transaction(function () use ($user, $targeUser, $target) {

            $chat = $this->chatRepository->store($user->uid, $targeUser->uid, $target->event_uid);

            $this->hookRepository->store($user->uid, $targeUser->uid, $target->event_uid);

            HookNotificationEvent::dispatch($user, $targeUser, $target->event_uid, $chat->uid);

            $this->notificationRepository->storeBoth($target, NotificationTypeEnum::HOOK);

            return [
                'superlike_credits' => $user->superlikes,
                'like_credits' => $user->likes,
            ];
        });
    }
}
