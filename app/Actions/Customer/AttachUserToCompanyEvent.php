<?php

declare(strict_types=1);

namespace App\Actions\Customer;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\UserEventRepository;
use App\Repositories\UserRepository;

final readonly class AttachUserToCompanyEvent
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CompanyRepository $companyRepository,
        private readonly UserEventRepository $userEventRepository,
    ) {}

    public function execute(User $user, string $company_uid): array
    {
        $user = $this->userRepository->updateUserCompany($user, $company_uid);
        $company = $this->companyRepository->getCompanyByUuid($company_uid);

        $event = $company->active_or_upcoming_event;

        if (! $event) {
            return [$user, null, $company];
        }

        $exist = $user->events()->wherePivot('event_uid', $event->uid)->exists();

        if (! $exist && $event->users->count() >= $company->limit_users) {
            throw new ApiException('limit_users_reached', 409);
        }

        $this->userEventRepository->attachUserToEvent($user, $event);

        return [$user, $event, $company];
    }
}
