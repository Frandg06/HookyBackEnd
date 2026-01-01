<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

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
    public function execute(InteractionDto $dto): array
    {
        $isHook = $this->targetUserRepository->isHook($dto);

        throw_if((! $isHook), RedirectException::targetUserNotAvailable());

        $target = $this->userRepository->findByUuid($dto->target_user_uid);

        return [
            'user' => TargetUserResource::make($target),
            'to_confirm' => false,
        ];
    }
}
