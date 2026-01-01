<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Actions\Customer\User\UpdatePasswordAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Http\Requests\Customer\User\UpdatePasswordRequest;

final class UpdatePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, UpdatePasswordRequest $request, UpdatePasswordAction $action)
    {
        $data = $request->only('password', 'old_password');

        $response = $action->execute($user, $data);

        return response()->json(['success' => true, 'resp' => $response], 200);
    }
}
