<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Models\User;
use App\Http\Resources\UserResource;

final readonly class MeAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user): UserResource
    {
        return $user->loadRelations()->toResource();
    }
}
