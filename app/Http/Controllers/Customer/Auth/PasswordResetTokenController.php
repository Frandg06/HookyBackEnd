<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Auth;

use App\Actions\Customer\Auth\PasswordResetTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Auth\PasswordResetTokenRequest;
use Illuminate\Http\Request;

final class PasswordResetTokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PasswordResetTokenRequest $request, PasswordResetTokenAction $action)
    {
        $email = $request->input('email');
        $action->execute($email);

        return $this->successResponse(__('i18n.password_reset_email'));
    }
}
