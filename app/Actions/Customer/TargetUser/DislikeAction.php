<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\Models\User;
use App\Dtos\InteractionDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\TargetUserRepository;

final readonly class DislikeAction
{
    public function __construct(private readonly TargetUserRepository $targetUserRepository) {}

    /**
     * Execute the action.
     */
    public function execute(User $user, InteractionDto $target): array
    {
        return DB::transaction(function () use ($user, $target) {

            $this->targetUserRepository->create($target->toArray());

            return [
                'superlike_credits' => $user->superlikes,
                'like_credits' => $user->likes,
            ];
        });
    }
}
