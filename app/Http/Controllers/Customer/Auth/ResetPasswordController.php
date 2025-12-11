<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\ResetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\ResetPasswordRequest;
use Illuminate\Http\Request;

final class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ResetPasswordRequest $request, ResetPasswordAction $action)
    {
        $action->execute($request->input('token'), $request->input('password'));
        return $this->successResponse(__('i18n.password_reset_success'));
    }
}
