<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Models\User;
use App\Dtos\InteractionDto;
use App\Repositories\UserRepository;
use App\Exceptions\RedirectException;
use App\Http\Resources\TargetUserResource;
use App\Repositories\TargetUserRepository;

final readonly class ShowTargetUserAction
{
    public function __construct(
        private UserRepository $userRepository,
        private TargetUserRepository $targetUserRepository
    ) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, InteractionDto $dto): array
    {

        $canSwipe = $this->resolveSwipeStatus($user, $dto);

        if ($canSwipe === null) {
            throw RedirectException::targetUserNotAvailable();
        }

        $targetUser = $this->userRepository->findByUuid($dto->target_user_uid);

        return [
            'user' => TargetUserResource::make($targetUser),
            'can_swipe' => $canSwipe,
        ];
    }

    /**
     * Resuelve si se puede hacer swipe o null si no aplica ninguna regla.
     */
    private function resolveSwipeStatus(User $user, InteractionDto $dto): ?bool
    {
        if ($this->targetUserRepository->isHook($dto)) {
            return false;
        }

        if ($this->targetUserRepository->receivedInteractionIsSuperLike($dto)) {
            return true;
        }

        if ($user->is_premium) {
            if ($this->targetUserRepository->receivedInteractionIsLike($dto)) {
                return true;
            }

            if ($this->targetUserRepository->receivedInteractionIsDislike($dto)) {
                return false;
            }
        }

        return null;
    }
}
