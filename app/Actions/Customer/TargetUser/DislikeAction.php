<?php

declare(strict_types=1);

namespace App\Actions\Customer\TargetUser;

use App\DTO\InteractionDto;
use App\Models\User;
use App\Repositories\TargetUserRepository;
use Illuminate\Support\Facades\DB;

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
                'super_like_credits' => $user->super_likes,
                'like_credits' => $user->likes,
            ];
        });
    }
}
