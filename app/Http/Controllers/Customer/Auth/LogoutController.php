<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Actions\Customer\Auth\LogoutAction;

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
