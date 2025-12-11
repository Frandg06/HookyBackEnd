<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\MeAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

final class MeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(MeAction $action)
    {
        return $this->successResponse('i18n.authenticated_user', $action->execute());
    }
}
