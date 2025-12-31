<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserEventRepository;

final readonly class EventAttachAction
{
    public function __construct(private readonly UserEventRepository $userEventRepository) {}

    /**
     * Execute the action.
     */
    public function execute(string $user_uid, string $event_uuid): array
    {
        return DB::transaction(function () use ($user_uid, $event_uuid): array {
            $event = $this->userEventRepository->findEventByUuid($event_uuid);

            if (! $event->is_active) {
                throw new ApiException('event_not_active', 404);
            }

            $user = $this->userEventRepository->attachUserToEvent($user_uid, $event);

            return [
                'like_credits' => $user->likes ?? 0,
                'super_like_credits' => $user->super_likes ?? 0,
                'event' => [
                    'is_active' => $event->is_active,
                    'uid' => $event->uid,
                    'name' => $event->name,
                    'st_date' => $event->st_date,
                    'end_date' => $event->end_date,
                    'is_finished' => $event->is_finished,
                ],
            ];
        });
    }
}
