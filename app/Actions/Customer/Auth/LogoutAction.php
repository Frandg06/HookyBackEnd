<?php

declare(strict_types=1);

namespace App\Actions\Customer\Auth;

use Illuminate\Support\Facades\DB;

final readonly class LogoutAction
{
    /**
     * Execute the action.
     */
    public function execute()
    {
        return DB::transaction(function () {
            //
        });
    }
}
