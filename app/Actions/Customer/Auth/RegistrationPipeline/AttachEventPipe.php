<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth\RegistrationPipeline;

use Closure;
use App\Repositories\UserEventRepository;

final class AttachEventPipe
{
    public function __construct(private readonly UserEventRepository $userEventRepository) {}

    /**
     * Handle the registration passable.
     */
    public function handle(RegisterUserPassable $passable, Closure $next): RegisterUserPassable
    {
        if (filled($passable->userDto->eventUid)) {

            $event = $this->userEventRepository->findEventByUuid($passable->userDto->eventUid);

            if (! $event || ! $event?->is_active) {
                return $next($passable);
            }

            $this->userEventRepository->attachUserToEvent($passable->user->uid, $event);
        }

        return $next($passable);

    }
}
