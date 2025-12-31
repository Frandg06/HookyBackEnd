<?php

declare(strict_types=1);

namespace App\Actions\Customer\User\Notification;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use App\Http\Resources\Customer\Interaction\HookMinifiedCollection;

final readonly class GetHookNotificationsAction
{
    public function __construct(private readonly UserRepository $user_repository) {}

    public function execute(User $user, int $page = 1): HookMinifiedCollection
    {
        return DB::transaction(function () use ($user, $page) {

            $hooks = $this->user_repository->getHooksNotifications($user, $page);

            return HookMinifiedCollection::make($hooks);
        });
    }
}
