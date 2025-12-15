<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Exceptions\ApiException;
use App\Repositories\UserEventRepository;
use Illuminate\Support\Facades\DB;

final readonly class EventAttachAction
{
    public function __construct(private readonly UserEventRepository $userEventRepository) {}

    /**
     * Execute the action.
     */
    public function execute(string $user_uid, string $event_uuid): void
    {
        DB::transaction(function () use ($user_uid, $event_uuid): void {
            $event = $this->userEventRepository->findEventByUuid($event_uuid);

            if (! $event->is_active) {
                throw new ApiException('event_not_active', 404);
            }

            $this->userEventRepository->attachUserToEvent($user_uid, $event);
        });
    }
}
