<?php

declare(strict_types=1);

namespace App\Actions\Customer\User;

use App\Models\User;
use App\Dtos\UpdateSettingsDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;

final readonly class UpdateSettingsAction
{
    public function __construct(private readonly UserRepository $user_repository) {}

    public function execute(User $user, UpdateSettingsDto $data): void
    {
        DB::transaction(function () use ($user, $data) {
            $this->user_repository->updateOrCreateUserSettings($user->uid, $data);
        });
    }
}
