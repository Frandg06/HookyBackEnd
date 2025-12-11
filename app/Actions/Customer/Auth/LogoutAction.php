<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use Illuminate\Support\Facades\Auth;

final readonly class LogoutAction
{
    /**
     * Execute the action.
     */
    public function execute()
    {
        Auth::logout();
    }
}
