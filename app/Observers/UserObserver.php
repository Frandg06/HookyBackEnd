<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Models\Settings;

final class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Settings::create([
            'user_uid' => $user->uid,
        ]);
    }
}
