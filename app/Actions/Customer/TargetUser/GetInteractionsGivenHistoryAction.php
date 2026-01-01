<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use App\Repositories\TargetUserRepository;
use App\Http\Resources\Customer\History\InteractionHistoryCollection;

final readonly class GetInteractionsGivenHistoryAction
{
    public function __construct(private TargetUserRepository $targetUserRepository) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, int $page = 1)
    {
        $interactions = $this->targetUserRepository->getInteractionsGivenHistory($user, $page);

        return InteractionHistoryCollection::make($interactions);
    }
}
