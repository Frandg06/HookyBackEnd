<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\User;

use App\Models\User;
use App\Dtos\UpdateUserDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Customer\User\UpdateUserAction;
use Illuminate\Container\Attributes\CurrentUser;
use App\Http\Requests\Customer\User\UpdateUserRequest;

final class UpdateUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(#[CurrentUser()] User $user, UpdateUserRequest $request, UpdateUserAction $action)
    {
        $data = new UpdateUserDto(
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            born_date: $request->string('born_date')->toString(),
            sexual_orientation: $request->string('sexual_orientation')->toString(),
            gender: $request->string('gender')->toString(),
            description: $request->string('description')->toString(),
        );

        $response = $action->execute($user, $data);

        $this->successResponse('i18n.user_updated', $response);
    }
}
