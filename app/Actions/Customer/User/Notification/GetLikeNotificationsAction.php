<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\Notification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use App\Http\Resources\Customer\Interaction\LikeMinifiedCollection;

final readonly class GetLikeNotificationsAction
{
    public function __construct(private readonly UserRepository $user_repository) {}

    public function execute(User $user, int $page = 1): LikeMinifiedCollection
    {
        return DB::transaction(function () use ($user, $page) {

            $likes = $this->user_repository->getLikesNotifications($user, $page);

            return LikeMinifiedCollection::make($likes);
        });
    }
}
