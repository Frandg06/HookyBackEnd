<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use App\Exceptions\ApiException;
use App\Repositories\UserRepository;
use App\Http\Resources\TargetUserResource;
use App\Repositories\TargetUserRepository;

final readonly class CheckPendingMatchAction
{
    public function __construct(
        private TargetUserRepository $targetUserRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Check if the authenticated user can confirm a match with the target user.
     *
     * @return array{user: TargetUserResource, to_confirm: bool}
     */
    public function execute(User $user, string $targetUserUid): array
    {
        $targetUser = $this->userRepository->findByUuid($targetUserUid);

        throw_if(! $targetUser, new ApiException('i18n.user_not_found', 404));

        $eventUid = $user->event->uid;

        if ($this->targetUserRepository->hasUserInteractedWith($user->uid, $targetUserUid, $eventUid)) {
            return $this->buildResponse($targetUser, to_confirm: false);
        }

        $this->validateCanViewPendingMatch($user, $targetUserUid, $eventUid);

        return $this->buildResponse($targetUser, to_confirm: true);
    }

    private function validateCanViewPendingMatch(User $user, string $targetUserUid, string $eventUid): void
    {
        $hasReceivedLike = $this->targetUserRepository->hasReceivedLikeFrom($targetUserUid, $user->uid, $eventUid);
        $hasReceivedSuperlike = $this->targetUserRepository->hasReceivedSuperlikeFrom($targetUserUid, $user->uid, $eventUid);

        // Superlike always visible
        if ($hasReceivedSuperlike) {
            return;
        }

        // Premium users can see likes
        if ($user->is_premium && $hasReceivedLike) {
            return;
        }

        // No valid interaction found
        throw new ApiException('i18n.not_available_user', 403);
    }

    /**
     * @return array{user: TargetUserResource, to_confirm: bool}
     */
    private function buildResponse(User $targetUser, bool $to_confirm): array
    {
        return [
            'user' => TargetUserResource::make($targetUser),
            'to_confirm' => $to_confirm,
        ];
    }
}
