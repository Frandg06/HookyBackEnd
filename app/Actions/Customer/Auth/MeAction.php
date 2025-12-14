<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;

final readonly class MeAction
{
    /**
     * Execute the action.
     */
    public function execute(User $user): UserResource
    {
        return $user->load([
            'userImages',
            'activeEvent',
            'notifications',
            'company',
        ])->toResource();
    }
}
