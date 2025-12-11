<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\LogoutAction;
use App\Http\Controllers\Controller;

final class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LogoutAction $action)
    {
        $action->execute();

        return $this->successResponse('i18n.logged_out');
    }
}
